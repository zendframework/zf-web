<?php
define('APPLICATION_PATH', 
    ((getenv('APPLICATION_PATH')) ? rtrim(getenv('APPLICATION_PATH'), '/') : realpath(dirname(__FILE__) . '/..')));
$include_path = array(
    '.',
    realpath(dirname(__FILE__) . '/../../framework/library'),
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $include_path));
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$config = new Zend_Config_Ini(__DIR__ . '/../etc/config.ini', 'production');

$rootdir = APPLICATION_PATH;
$tmpdir  = $rootdir . '/cache/faq/tmp';
if (!is_dir($tmpdir)) {
    mkdir($tmpdir, 0777, true);
}

$htmldir = $rootdir . '/cache/faq/ZFFAQ';
if (!is_dir($htmldir)) {
echo "Fetching FAQ files\n";
    $user   = $config->jira->credentials->username;
    $pass   = $config->jira->credentials->password;
    $cxn    = new Zend_XmlRpc_Client('http://framework.zend.com/wiki/rpc/xmlrpc');
    $client = $cxn->getProxy()->confluence1;
    $token  = $client->login($user, $pass);
    $url    = $client->exportSpace($token, 'ZFFAQ', 'TYPE_HTML');
echo "    Generated fetch zip archive at $url\n";

echo "    Logging in to website\n";
    $client = $cxn->getHttpClient();
    $client->setUri('http://framework.zend.com/wiki/login.action');
    $client->setCookieJar();
    $client->resetParameters();
    $client->setParameterPost(array(
        'os_username' => $user,
        'os_password' => $pass,
    ));
    $result = $client->request('POST');
    
echo "    Fetching zip archive\n";
    $client->setUri($url);
    $client->resetParameters();
    $client->setConfig(array(
        'timeout' => 3600,
    ));
    $response = $client->request('GET');
    if ($response->isError()) {
        exit("Unable to download FAQ at $url; received status " . $response->getStatus() . ' with message ' . $response->getMessage());
    }
echo "    Received zip archive\n";
    $zip = $response->getRawBody();
    $zipFile = $rootdir . '/cache/faq/ZFFAQ.zip';
    file_put_contents($zipFile, $zip);
    $zip = new ZipArchive;
    $res = $zip->open($zipFile);
    if (true !== $res) {
        exit('Unable to open zip file: ' . $res);
    }
    $zip->extractTo($rootdir . '/cache/faq/');
    $zip->close();
echo "    Extracted zip archive\n";
}

$dom = new Zend_Dom_Query();
foreach (glob($htmldir . '/*.html') as $file) {
    $filename = basename($file);
    echo "Processing $filename\n";
    $html = file_get_contents($file);
    $dom->setDocument($html);
    $results = $dom->query('td.pagebody');
    $content = $results->current();
    $doc = $content->ownerDocument;
    $xml = $doc->saveXML($content);
    $xml =<<<EOS
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
<body>
$xml
</body>
EOS;
    file_put_contents($tmpdir . '/' . $filename, $xml);
}

foreach (glob($tmpdir . '/*.html') as $file) {
    $filename = basename($file);
    echo "Processing $filename\n";

    if ('index.html' == $filename) {
        unlink($file);
        continue;
    }

    $html = file_get_contents($file);
    $dom->setDocument($html);

    echo "    Checking for comments... ";
    $results = $dom->queryXpath('//a[@name="comments"]/ancestor::div[@class="tabletitle"]');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "Removing comments title... ";
        foreach ($results as $node) {
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    }
    $results = $dom->queryXpath('//a[starts-with(@name, "comment-")]/ancestor::table');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "Removing comments table... ";
        foreach ($results as $node) {
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    }
    echo "\n";
    $results = $dom->queryXpath('//a[@name="comments"]');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "Removing comments anchors... ";
        foreach ($results as $node) {
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    }

    $results = $dom->query('div.pagesubheading');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing subheading\n";
        $node    = $results->current();
        $parent  = $node->parentNode;
        $parent->removeChild($node);
        $dom->setDocument($doc->saveXML($doc->documentElement));
    }

    $results = $dom->query('img');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing images";
        foreach ($results as $node) {
    echo " .";
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $results = $dom->query('script');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing scripts";
        foreach ($results as $node) {
    echo " .";
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $results = $dom->query('style');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing styles";
        foreach ($results as $node) {
    echo " .";
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $results = $dom->query('link');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing links";
        foreach ($results as $node) {
    echo " .";
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $results = $dom->query('.pageheader');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Removing header";
        foreach ($results as $node) {
    echo " .";
            $parent = $node->parentNode;
            $parent->removeChild($node);
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $results = $dom->query('p span.cloakToggle');
    $doc     = $results->getDocument();
    if (0 < count($results)) {
    echo "    Reformatting question headers";
        foreach ($results as $node) {
            $parent = $node->parentNode;
            $id     = $node->getAttribute('id');
            foreach ($parent->childNodes as $child) {
                if (isset($child->tagName) && ('b' == $child->tagName)) {
    echo " .";
                    $linkId   = str_replace('tgl_', 'link_', $id);
                    $tglId    = str_replace('tgl_', 'ind_', $id);
                    $tglNode  = $doc->createElement('img');
                    $tglNode->setAttribute('id', $tglId);
                    $tglNode->setAttribute('border', '0');
                    $tglNode->setAttribute('src', '/images/navigate_right_10.gif');
                    $tglNode->setAttribute('class', 'faq-toggle');
                    $linkNode = $doc->createElement('a');
                    $linkNode->setAttribute('id', $linkId);
                    $linkNode->setAttribute('class', 'cloakToggle');
                    $linkNode->setAttribute('href', "javascript:zend.toggle('" . $linkId . "');");
                    $parent->appendChild($linkNode);
                    $linkNode->appendChild($tglNode);
                    $linkNode->appendChild($child);
                }
            }
        }
        $dom->setDocument($doc->saveXML($doc->documentElement));
    echo " DONE!\n";
    }

    $xml = $doc->saveXML($doc->documentElement);
    $xml = preg_replace('#</?html>#i', '', $xml);
    $xml = preg_replace('#</?head>#i', '', $xml);
    $xml = preg_replace('#</?meta[^>]*>#i', '', $xml);
    $xml = preg_replace('#</?body>#i', '', $xml);
    $xml = preg_replace('#</?td[^>]*>#i', '', $xml);
    $xml = preg_replace('/="\s+page([^ ]*)\s+"/', '="faq$1"', $xml);
    $xml = preg_replace('/="\s+([^ ]+)\s+"/', '="$1"', $xml);
    $xml = preg_replace('#<div class="faqheader">.*?(?:<span class="faqtitle">).*?(?:</span>).*?(?:</div>)#s', '', $xml);
    $xml = str_replace(' style="display: none"', '', $xml);
    $xml = preg_replace('/^\s+(.*?)\s+$/s', '$1', $xml);
    file_put_contents($file, $xml);
}

echo "Creating view script...";
$html = '';
$home = file_get_contents($tmpdir . '/Home.html');
$dom->setDocument($home);
$links = $dom->query('li a');
foreach ($links as $node) {
    $href    = $node->getAttribute('href');
    $title   = $node->getAttribute('title');
    $content = file_get_contents($tmpdir . '/' . $href);
    $html .= '<div class="faq" dojoType="dijit.TitlePane" open="false" title="' . $title . '">' . $content . '</div>';
}

if (file_exists($rootdir . '/views/scripts/about/_faq.phtml')) {
    unlink($rootdir . '/views/scripts/about/_faq.phtml');
}
file_put_contents($rootdir . '/views/scripts/about/_faq.phtml', $html);
echo " DONE!\n";

echo "Cleaning up...";
shell_exec('rm -Rf ' . $tmpdir);
shell_exec('rm -Rf ' . $htmldir);
unlink($zipFile);
echo " DONE!\n";
