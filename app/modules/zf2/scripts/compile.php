<?php
/**
 * Compile the ZF2 blog
 *
 * Compiles the ZF2 blog from posts in the "../posts" subdirectory; sample 
 * posts are provided to indicate format.
 *
 * Options:
 * - -? or --help:          display the usage message
 * - -v or --verbose:       log progress to STDOUT
 * - -d or --dev:           specify development mode
 * - -h or --host=[string]: specify an alternate host for generated URLs
 */

/**
 * Setup autoloading
 */
set_include_path(implode(PATH_SEPARATOR, array(
    '.',
    __DIR__ . '/../../../../../framework/library',
    get_include_path(),
)));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

/**
 * Configuration variables
 *
 * - isDevelopment: whether or not we're in development. If so, a flag will be 
 *   set in the disqus comments.
 * - httpHost: The host name to use for URLs. In production, this should be "framework.zend.com"; 
 *   when in development or testing, this should be a subdomain.
 */
$isDevelopment = false;
$httpHost      = 'framework.zend.com';
$verbose       = false;

/**
 * Get command line options
 */
try {
    $getopt = new Zend_Console_Getopt(array(
        'help|?'    => 'Print this help message',
        'dev|d'     => 'Use Development mode',
        'host|h-s'  => 'Hostname to use for URL generation',
        'verbose|v' => 'Turn on verbosity (messages)',
    ));
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit(1);
}

// Did they ask for help?
if ($getopt->getOption('?')) {
    echo $getopt->getUsageMessage();
    exit(0);
}

// Setup logging
// Verbose?
if ($getopt->getOption('v')) {
    $verbose = true;
    echo "Verbosity enabled\n";
}
$log = new Zend_Log();
if ($verbose) {
    $writer = new Zend_Log_Writer_Stream('php://stdout');
} else {
    $writer = new Zend_Log_Writer_Null();
}
$formatter = new Zend_Log_Formatter_Simple('%message%' . PHP_EOL);
$writer->setFormatter($formatter);
$log->addWriter($writer);


if ($getopt->getOption('d')) {
    $isDevelopment = true;
    $log->info('Development mode enabled');
}

if (false !== ($opt = $getopt->getOption('h'))) {
    if (!empty($opt)) {
        $httpHost = $opt;
    }
}
$log->info('Hostname set to ' . $httpHost);

require_once 'EntryFilter.php';
require_once 'EntryValidator.php';
require_once 'EntryNormalizer.php';

$dir       = new DirectoryIterator(__DIR__ . '/../posts');
$filtered  = new EntryFilter($dir);
$validator = new EntryValidator();
$entries   = new SplPriorityQueue();

/**
 * @todo Skip entries that already exist? or mark as "exists"?
 */
$log->info('Scanning for posts...');
foreach ($filtered as $file) {
    $filename = $file->getRealpath();
    $entry = include($filename);
    if (!$validator->isValid($entry)) {
        file_put_contents(
            'php://stderr',
            "Entry in '" . $filename . "' is invalid\n"
        );
        continue;
    }
    $entry = EntryNormalizer::normalize($entry);
    if (!$entry) {
        file_put_contents(
            'php://stderr',
            "Entry in '" . $filename . "' could not be normalized\n"
        );
        continue;
    }
    $log->info(sprintf('    Found post in %s', $filename));
    $entries->insert($entry, $entry->date);
}

// - Loop through discovered entries and create post view script
$view = new Zend_View();
$view->addScriptPath(__DIR__ . '/../views/scripts')
     ->addHelperPath(__DIR__ . '/../views/helpers', 'Zf2_View_Helper');

$log->info('Creating blog entry view scripts...');
foreach (clone $entries as $entry) {
    $view->clearVars();
    $view->assign(array(
        'entry'       => $entry,
        'development' => $isDevelopment,
        'host'        => $httpHost,
    ));
    $page = $view->render('blog/blog-entry.phtml');

    $script = __DIR__ . '/../views/scripts/blog/' . strtolower($entry->stub) . '.phtml';
    file_put_contents($script, $page);
    $log->info('    created '. $script);
}

// - Create paginated entries:
//   - pass queue to a Paginator
$log->info('Generating list pages...');
$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Iterator(clone $entries));
$pages     = $paginator->getPages();
$log->info(sprintf("    Found %d pages", $pages->pageCount));

for ($page = 1; $page <= $pages->pageCount; $page++) {
//   - create a page per page of paginator
//     - each page has summaries for each item
//     - each page has a rendered paginator
    $view->clearVars();
    $view->assign(array(
        'entries' => $entries,
        'page'    => $page,
    ));
    $listContent   = $view->render('blog/blog-list.phtml');

    $script = __DIR__ . '/../views/scripts/blog/list-p' . $page . '.phtml';
    file_put_contents($script, $listContent);
    $log->info("    created " . $script);
}

// - Create feed off of first page of entries
$log->info('Generating feed');
$paginator = new Zend_Paginator(new Zend_Paginator_Adapter_Iterator(clone $entries));
$paginator->setCurrentPageNumber(1);
$view->clearVars();
$view->assign(array(
    'entries' => $paginator,
    'host'    => $httpHost,
));
$feed   = $view->render('blog/blog-feed.phtml');
$script = __DIR__ . '/../views/scripts/blog/feed.xml';
file_put_contents($script, $feed);

$log->info('DONE!');
