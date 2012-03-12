<?php
define('APPLICATION_PATH', dirname(dirname(__FILE__)));
date_default_timezone_set('UTC');
ini_set('display_errors', false);
ini_set('memory_limit', -1);
error_reporting(E_ALL | E_STRICT);
set_time_limit(0);
$paths = array(
    '.',
    dirname(__FILE__) . '/../../framework',
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $paths));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

$env = (getenv('APPLICATION_ENV')) ? getenv('APPLICATION_ENV') : 'production';

// Get configuration
$config    = new Zend_Config_Ini(dirname(__FILE__) . '/../etc/config.ini', $env);
Zend_Registry::set('config', $config);

$allLangs  = false;
$indexPath = false;
$lang      = false;
$manPath   = false;
$useConfig = false;
$version   = false;

// Get request parameters
$opts = new Zend_Console_Getopt(array(
    'all|a'        => 'Index all languages',
    'lang|l-s'     => 'Manual language to index',
    'man-path|m-s' => 'Path to manual sources; defaults to configuration',
    'path|p-s'     => 'Path in which to create index',
    'version|v-s'  => 'Manual version to index',
));

try {
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit;
}

if (!isset($opts->l) && !isset($opts->a)) {
    echo "Requires at least one of --lang or --all\n\n";
    echo $opts->getUsageMessage();
    exit;
}

if (isset($opts->a)) {
    $allLangs = true;
}

if (isset($opts->l)) {
    $lang = $opts->l;
}

if (isset($opts->m)) {
    $manPath = $opts->m;
}

if (isset($opts->p)) {
    $indexPath = $opts->p;
}

$version   = (isset($opts->v)) ? $opts->v : 'current';

// Log to file and STDOUT
$logDir    = $config->log->path;
if (!file_exists($logDir)) {
    mkdir($logDir, 0777, true);
}
$log = new Zend_Log(new Zend_Log_Writer_Stream($logDir . '/index-' . $version . '-manual.log'));
$log->addWriter(new Zend_Log_Writer_Stream('php://stdout'));
$log->info('Beginning indexing operations');

// Get manual version
require_once APPLICATION_PATH . '/models/ManualModel.php';
$model     = new ManualModel();
$version   = $model->getManualVersion($version);
$log->info(sprintf('Using manual version %s', $version));

// Create index path, and ensure it's empty
if (!$indexPath) {
    $log->info('Index path not provided; retrieving from configuration');
    $indexPath = sprintf('%s/%s', $config->index->manual->path, (string) $version);
    if ('/' != $indexPath[0]) {
        $indexPath = sprintf('%s/%s', $config->index->basePath, $indexPath);
    }
    $useConfig = true;
}
$log->info(sprintf('Indexing to directory "%s"; ensuring exists and empty', $indexPath));
ensureDirectoryExists($indexPath, true);

// Determine what language(s) we're indexing
if ($manPath && (!file_exists($manPath) || !is_dir($manPath))) {
    echo "The path specified for the manual source ($manPath) is invalid\n";
    exit(42);
}
if (!$manPath) {
    $log->info('No manual source path given; retrieving from configuration');
    $manPath   = sprintf($config->manual->source, $model->getVersion($version));
}
$log->info(sprintf('Using directory "%s" as manual source', $manPath));

$log->info('Computing langages to index');
$langs     = array();
foreach (new DirectoryIterator($manPath) as $item) {
    if (!$item->isDir() || $item->isDot()) {
        continue;
    }

    $langs[] = $item->getFilename();
}

if ($allLangs) {
    $indexLangs = $langs;
} elseif (in_array($lang, $langs)) {
    $indexLangs = array($lang);
} else {
    echo "You selected an invalid language. Please use choose from one of the following:\n";
    echo "    " . implode(', ', $langs) . "\n";
    exit(42);
}

// Index!
Zend_Search_Lucene_Analysis_Analyzer::setDefault(
    new Zend_Search_Lucene_Analysis_Analyzer_Common_Utf8_CaseInsensitive()
);

$log->info('Creating index');
try {
    $index = Zend_Search_Lucene::open($indexPath);
} catch (Zend_Search_Lucene_Exception $e) {
    $index = Zend_Search_Lucene::create($indexPath);
}
foreach ($indexLangs as $lang) {
    $baseUrl = '/manual/' . $version . '/' . $lang . '/';
    $log->info(sprintf('INDEXING LANGUAGE: %s', $lang));

    foreach (new DirectoryIterator($manPath . '/' . $lang) as $file) {
        $fileName = $file->getFilename();
        if ($file->isDir()
            || $file->isDot()
            || ('.html' != substr($fileName, -5) && '.phtml' != substr($fileName, -6))
        ) {
            continue;
        }

        // Check to see if the file exists
        $url    = $baseUrl . $fileName;
        $docIds = $index->termDocs(new Zend_Search_Lucene_Index_Term($url, 'url'));
        foreach ($docIds as $docId) {
            if ($opts->s) {
                // Should we skip, or reindex?
                $log->info(sprintf('    Found "%s" in index; skipping', $url));
                continue 2;
            }
            $log->info(sprintf('    Found "%s" in index; removing so we can reindex', $url));
            $index->delete($docId);
        }

        $log->info(sprintf('    Indexing: %s', $url));

        $doc = Zend_Search_Lucene_Document_Html::loadHTMLFile($file->getPathname());
        $doc->addField(Zend_Search_Lucene_Field::Text('url',  $url));
        $doc->addField(Zend_Search_Lucene_Field::Text('lang', $lang));
        $index->addDocument($doc);
    }
}

$log->info('OPTIMIZING INDEX');

$index->optimize();

$log->info('UPDATING PERMISSIONS');

foreach (new DirectoryIterator($indexPath) as $file) {
    if ($file->isDir() || $file->isDot()) {
        continue;
    }
    chmod($file->getPathname(), 0666);
}

$log->info('INDEX COMPLETE');

if ($useConfig && isset($_ENV['USER']) && 'root' == $_ENV['USER']) {
    $log->info('CHANGING OWNERSHIP ON INDEX');
    shell_exec('chown -R www.www ' . $indexPath);
}

if (!$useConfig) {
    $log->info('Please make sure you change ownership on the index to match your web server user and group.');
    $log->info('Sync the index to the final destination using:');
    $log->info(sprintf('    rsync -aC --delete %s /final/index/path', $indexPath));
}

/**
 * Functions 
 */

/**
 * Ensure a directory exists
 *
 * - If the path exists and is not a directory, raises an exception
 * - If the path exists, is a directory, and $clean is false, returns immediately
 * - If the path exists, is a directory, and $clean is true, recursively removes
 *   the directory, and then re-creates it
 * - If the path does not exist, creates it
 *
 * @param  string $path 
 * @param  bool $clean 
 * @return void
 * @throws Exception
 */
function ensureDirectoryExists($path, $clean = false)
{
    if (file_exists($path) && !is_dir($path)) {
        throw new Exception(sprintf(
            'Need a directory at location "%s"; file exists there already. Cannot proceed.',
            $path
        ));
    }

    if (file_exists($path) && is_dir($path)) {
        if (!$clean) {
            // Directory exists, and we didn't indicate we need a clean slate
            return;
        }
        recursiveDelete($path);
    }

    // emulate mkdir -p
    mkdir($path, 0777, true);
}

/**
 * Delete a file or recursively delete a directory
 *
 * Pulled from {@link http://php.net/unlink}
 *
 * @param string $str Path to file or directory
 */
function recursiveDelete($path)
{
    if (is_file($path)) {
        return @unlink($path);
    } elseif (is_dir($path)) {
        $scan = glob(rtrim($path,'/') . '/*');
        foreach ($scan as $child){
            recursiveDelete($child);
        }
        return @rmdir($path);
    }
}
