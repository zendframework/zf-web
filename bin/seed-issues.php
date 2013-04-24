<?php
use Michelf\MarkdownExtra as Markdown;
use Phly\Mustache\Mustache;
use Phly\Mustache\Pragma;

require __DIR__ . '/../vendor/autoload.php';

$purifierConfig = HTMLPurifier_Config::createDefault();
$purifierConfig->set('HTML.Doctype', 'XHTML 1.0 Transitional');
$purifier = new HTMLPurifier($purifierConfig);

$mustache = new Mustache();
$mustache->setTemplatePath(__DIR__ . '/../data/');
$mustache->getRenderer()->addPragma(new Pragma\ImplicitIterator());

$markdown = new Markdown();

$attachDir = __DIR__ . '/../public/issues/';
$issueDir  = __DIR__ . '/../data/issues/';
$publicDir = __DIR__ . '/../module/Issues/view/issues/issues/';

foreach (new GlobIterator($issueDir . '**/*.json', FilesystemIterator::KEY_AS_FILENAME|FilesystemIterator::CURRENT_AS_PATHNAME) as $key => $path) {
    echo "Processing $key...\n";
    $filename = $publicDir . basename($key, '.json') . '.phtml';
    if (file_exists($filename)) {
        continue;
    }

    $json = file_get_contents($path);
    $data = json_decode($json);

    echo "    Processing...\n";
    // Tasks:
    // - process description
    $description = '';
    if (isset($data->fields->description->value)) {
        $description = process_content(
            $data->fields->description->value,
            $markdown,
            $purifier
        );
    }

    // - reporter
    $reporter = array(
        'name' => isset($data->fields->reporter->value) ? $data->fields->reporter->value->displayName : '',
        'nick' => isset($data->fields->reporter->value) ? $data->fields->reporter->value->name : '',
    );
    if (empty($reporter['name']) && empty($reporter['nick'])) {
        $reporter = array();
    }

    // - assignee
    $assignee = array(
        'name' => isset($data->fields->assignee->value) ? $data->fields->assignee->value->displayName : '',
        'nick' => isset($data->fields->assignee->value) ? $data->fields->assignee->value->name : '',
    );
    if (empty($assignee['name']) && empty($assignee['nick'])) {
        $assignee = array();
    }

    // - fix version(s)
    $versions = array();
    foreach ($data->fields->fixVersions->value as $version) {
        $versions[] = array('version' => sprintf('%s (%s)', $version->name, isset($version->userReleaseDate) ? $version->userReleaseDate : ''));
    }

    // - process components/tags
    $tags = array();
    foreach ($data->fields->components->value as $component) {
        $tags[] = $component->name;
    }
    foreach ($data->fields->labels->value as $label) {
        $tags[] = $label;
    }
    $tags = array_unique($tags);
    array_walk($tags, function (&$item, $key) {
        $item = array('tag' => "$item");
    });

    // - process related issues
    $related = array();
    foreach ($data->fields->links->value as $value) {
        $related[] = array('key' => $value->issueKey);
    }

    // - process comment bodies
    $comments = array();
    foreach ($data->fields->comment->value as $comment) {
        $comment->body = process_content(
            $comment->body,
            $markdown,
            $purifier
        );
        $comments[] = $comment;
    }

    // - process attachments (download to file, create URL)
    $attachments = array();
    foreach ($data->fields->attachment->value as $attachment) {
        echo "    Downloading attachment {$attachment->content}\n";
        $path = process_attachment($attachment->content, $attachDir);
        $attachments[] = array(
            'url' => $path,
            'filename' => $attachment->filename,
        );
    }

    $model = array(
        'key'          => $data->key,
        'summary'      => $data->fields->summary->value,
        'issuetype'    => $data->fields->issuetype->value->name,
        'status'       => $data->fields->status->value->name,
        'fix_versions' => $versions,
        'reporter'     => $reporter,
        'created'      => $data->fields->created->value,
        'updated'      => $data->fields->updated->value,
        'assignee'     => $assignee,
        'tags'         => $tags,
        'related'      => $related,
        'attachments'  => $attachments,
        'description'  => $description,
        'comments'     => $comments,
    );

    echo "    Rendering...\n";
    $markup = $mustache->render('issue', $model);

    echo "    Writing...\n";
    file_put_contents($filename, $markup);
}

function process_content($content, Markdown $markdown, HTMLPurifier $purifier)
{
    $content = jira_filter_to_markdown($content);
    $content = $markdown->transform($content);
    $content = $purifier->purify($content);
    return $content;
}

function process_attachment($url, $attachmentPath)
{
    $relative = preg_replace('#^http://(fw02|framework)\.zend\.com/issues#', '', $url);
    $filename = $attachmentPath . $relative;
    if (file_exists($filename)) {
        return '/issues' . $relative;
    }

    $dirname  = dirname($filename);
    if (!is_dir($dirname)) {
        mkdir($dirname, 0755, true);
    }
    file_put_contents($attachmentPath . $relative, file_get_contents($url));
    return '/issues' . $relative;
}

function jira_filter_to_markdown($content) 
{
    $content = preg_replace('#\[([^|]+)\|(https?:\/\/[^\]]+)\]#m', '<a href="$2" rel="nofollow">$1</a>', $content);
    $content = preg_replace('#({code.*})(\n+<\?php)#m', '```$2', $content);
    $content = preg_replace('#{code.*}#', '```', $content);
    $content = preg_replace('#{/code}#', '```', $content);
    $content = preg_replace('#{noformat.*}#', '````', $content);
    $content = preg_replace('#````(.*?)````#s', '<pre class="literal">$1</pre>', $content);
    $content = preg_replace('#```(.*?)```#s', '<pre class="highlight"><code>$1</code></pre>', $content);
    $content = autolink_text($content);
    return $content;
}

/**
 * @see http://stackoverflow.com/a/1971451/31459
 */
function autolink_text($text)
{
    $pattern  = '#(?<!")\b(([\w-]+://?|www[.])[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/)))#';
    return preg_replace_callback($pattern, 'autolink_text_callback', $text);
}

/**
 * @see http://stackoverflow.com/a/1971451/31459
 */
function autolink_text_callback($matches)
{
    $max_url_length           = 50;
    $max_depth_if_over_length = 2;
    $ellipsis                 = '&hellip;';
    $url_full                 = $matches[0];
    $url_short                = '';

    if (strlen($url_full) > $max_url_length) {
        $parts = parse_url($url_full);
        if (!isset($parts['host'])) {
            return $url_full;
        }

        $scheme    = isset($parts['scheme']) ? $parts['scheme'] : 'http';
        $url_short = $scheme . '://' . preg_replace('/^www\./', '', $parts['host']) . '/';

        $path_components = explode('/', trim($parts['path'], '/'));
        foreach ($path_components as $dir) {
            $url_string_components[] = $dir . '/';
        }

        if (!empty($parts['query'])) {
            $url_string_components[] = '?' . $parts['query'];
        }

        if (!empty($parts['fragment'])) {
            $url_string_components[] = '#' . $parts['fragment'];
        }

        for ($k = 0; $k < count($url_string_components); $k++) {
            $curr_component = $url_string_components[$k];
            if ($k >= $max_depth_if_over_length || strlen($url_short) + strlen($curr_component) > $max_url_length) {
                if ($k == 0 && strlen($url_short) < $max_url_length) {
                    // Always show a portion of first directory
                    $url_short .= substr($curr_component, 0, $max_url_length - strlen($url_short));
                }
                $url_short .= $ellipsis;
                break;
            }
            $url_short .= $curr_component;
        }

    } else {
        $url_short = $url_full;
    }

    return "<a rel=\"nofollow\" href=\"$url_full\">$url_short</a>";
}
