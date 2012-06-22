<?php
/**
 * ARGUMENTS:
 *    --version="X.Y.Z"               ZF release version
 *    --tmp-dir=./build               path to build directory
 *    --cleanup=0                     cleanup afterwards? (default: no)
 *    --zf-src="path to ZF directory" If provided, the path to a ZF directory
 *                                    If not provided, will pull from SVN, using
 *                                    the --version specified
 *
 * --version is required
 *
 * ALGORITHM:
 * Determine and create build dir
 *   - $viewPath = $buildDir/$manualVersion
 * Export build tools
 *   - grab from svn
 *   - export to $buildDir/phd 
 * Determine manual sources
 *   - should allow passing in directory as option
 *   - otherwise, grab from svn based on release version, and export to $buildDir/src
 *   - determine if we have Extras, and their location; if so, prepare dom nodes with extras chapters
 * Build the manual 
 *   - foreach language:
 *     - chdir to the language's manual
 *     - autoconf + configure + make manual.xml
 *     - load manual.xml via DOM
 *     - Add extras chapters, if any
 *     - Strip index node from source
 *     - Save manual.xml (ideally, as a new file "manual.processed.xml")
 *     - Create component TOC as view script under $buildDir/$manualVersion/$language
 *     - Convert manual source to docbook 5, creating new manual source
 *     - Build zfonline docs from db5 manual source
 *     - Fix image links in built source
 *     - Sync built view scripts in output/zf-online-chunked-xhtml into $buildDir/$manualVersion/$language
 *     - If cleanup, remove output directory from current directory, remove .processed.xml, .db5.xml manual files
 * If cleanup...
 *   - Remove $buildDir/phd directory
 *   - Remove $buildDir/src, if created
 * Print completion message
 *   - Tell user to create index
 *   - Tell user to rsync -aC --delete $buildDir/$manualVersion to $config->manual->views/manual/
 * ####################################################################
 */
if (version_compare(PHP_VERSION, '5.3.0', '<')) {
    echo "This script requires a PHP version of at least 5.3.0\n";
    exit(42);
}

ob_start();
phpinfo(INFO_GENERAL);
$info = ob_get_clean();
if (!preg_match('#\'--prefix=(?P<path>[^\']+)\'#', $info, $matches)) {
    echo "Unable to determine path to PHP; aborting\n";
}
$php = $matches['path'] . '/bin/php';

define('APPLICATION_PATH', dirname(dirname(__FILE__)));
define('APPLICATION_ENV', 
    (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production')
);
date_default_timezone_set('GMT');
ini_set('display_errors', false);
ini_set('memory_limit', -1);
error_reporting(E_ALL | E_STRICT);
set_time_limit(0);
$paths = array(
    '.',
    realpath(dirname(__FILE__) . '/../../framework/library'),
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $paths));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

// CONFIGURATION
define('CWD', realpath(getcwd()));
define('ZF_SVN', 'http://framework.zend.com/svn/framework/standard');
define('ZFBUILD_SVN', 'http://framework.zend.com/svn/framework/build-tools/trunk/build-tools');

$languages = array(
    'en', 'de', 'fr', 'ja', 'ru', 'zh',
);

$buildDir   = './build';
$cleanup    = false;
$extrasPath = false;
$manPath    = false;
$noExtras   = true;
$version    = null;
$viewPath   = null;
$zfSource   = false;

$opts = new Zend_Console_Getopt(array(
    'version|v=s' => 'Zend Framework release tag version to use (required for building docs)',
    'tmp-dir|t-s' => 'Name of build directory to use (defaults to ./build)',
    'cleanup|x'   => 'Cleanup afterwards?',
    'zf-src|z-s'  => 'Zend Framework source path; pulls from SVN if not specified',
));

try {
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit;
}

if (!isset($opts->v)) {
    echo "MUST specify version (-v)\n\n";
    echo $opts->getUsageMessage();
    exit(42);
}
$version = $opts->v;

if (isset($opts->t)) {
    $buildDir = $opts->t;
}

if (isset($opts->x)) {
    $cleanup = true;
}

if (isset($opts->z)) {
    $zfSource = $opts->z;
}

ensureDirectoryExists($buildDir);
$buildDir = realpath($buildDir);

$config     = new Zend_Config_Ini(dirname(__FILE__) . '/../etc/config.ini.dist', 'production');
Zend_Registry::set('config', $config);
require_once dirname(__FILE__) . '/../models/ManualModel.php';
$model      = new ManualModel();
$manVersion = $model->getManualVersion($version);

echo "CREATING temporary directory for manual build\n";
$viewPath = $buildDir . '/' . $manVersion;
ensureDirectoryExists($viewPath, true);

echo "EXPORTING manual build tools\n";
$phdPath = $buildDir . '/phd';
if (file_exists($phdPath)) {
    recursiveDelete($phdPath);
}
passthru('svn export ' . ZFBUILD_SVN . "/docs $buildDir/phd", $failed);
if ($failed) {
    echo "FAILED EXPORTING PhD docbook tools\n\n";
    exit(42);
}

echo "LOCATING manual sources\n";
if ($zfSource) {
    // Using manual source provided via CLI option
    if (!file_exists($zfSource) || !is_dir($zfSource)) {
        echo "INVALID ZF SOURCE specified; path does not exist\n\n";
        exit(42);
    }

    $manPath = $zfSource . '/documentation/manual';
    if (!file_exists($manPath) || !is_dir($manPath)) {
        echo "INVALID ZF SOURCE specified; manual directory does not exist\n\n";
        exit(42);
    }

    $extrasPath = $zfSource . '/extras/documentation/manual/en';
    if (file_exists($extrasPath) && is_dir($extrasPath)) {
        $noExtras = false;
    } else {
        $extrasPath = false;
    }
} else {
    // Grab manual source from SVN
    echo "    EXPORTING (standard) manual sources from SVN\n";
    $manPath = $buildDir . '/src/';
    passthru('svn export ' . ZF_SVN . "/tags/release-$version/documentation/manual $manPath", $failed);
    if ($failed) {
        echo "FAILED EXPORTING $lang manual source from tag\n\n";
        exit(42);
    }

    echo "    EXPORTING (extras) manual sources\n";
    $extrasUri = ZF_SVN . '/tags/release-' . $version . '/extras/documentation/manual/en/';
    $info = shell_exec('svn info ' . $extrasUri . ' 2>&1|grep -o "^Revision: [0-9]*" | sed -e "s/Revision: //"');
    if (empty($info)) {
        echo "    No extras manual sources found at $extrasUri\n";
        $noExtras = true;
    } else {
        $extrasPath = $buildDir . '/src/extras';
        passthru("svn export $extrasUri $extrasPath", $failed);
        if ($failed) {
            echo "    Failure exporting extras (via url $extrasUri)\n";
            exit(42);
        }
        $noExtras = false;
    }
}

if (!$noExtras) {
    $dom = new DOMDocument();
    $dom->load("$extrasPath/manual.xml.in");
    $nodeList       = $dom->getElementsByTagName('chapter');
    $extrasChapters = array();
    for ($i = 0; $i < $nodeList->length; $i++) {
        $node = $nodeList->item($i);
        $extrasChapters[] = $node->cloneNode(true);
    }
}

foreach ($languages as $lang) {
    echo "GENERATING manual view scripts for language '$lang'\n";

    // Create view script path
    echo "    CREATING view script directory\n";
    ensureDirectoryExists($viewPath . '/' . $lang, true);

    // Enter into manual source directory
    chdir("$manPath/$lang");

    // Prepare manual build
    echo "    PREPARING manual sources for build\n";
    passthru("autoconf && sh ./configure && make manual.xml");
    $doc = new DOMDocument();
    $doc->load('manual.xml');

    // Add Extras chapters into source
    if (!$noExtras) {
        echo "    COPYING extras manual sources to manual source for language $lang\n";
        passthru("cp $extrasPath/module_specs/*.xml module_specs/", $failed);
        if ($failed) {
            echo "    FAILED COPYING extras manual sources to language $lang\n";
            exit(42);
        }
        echo "    ADDING extras manual chapters to manual source for language $lang\n";
        $xpath = new DOMXPath($doc);
        if (version_compare($manVersion, '1.9.7', '<=')) {
            $parts = $xpath->query("//chapter");
            $lastChapter   = $parts->item($parts->length - 1);
            $chapterParent = $lastChapter->parentNode;
            $appendixNode  = $lastChapter->nextSibling;
            if (0 < $parts->length) {
                foreach ($extrasChapters as $chapter) {
                    $new = $doc->importNode($chapter, true);
                    $chapterParent->insertBefore($new, $appendixNode);
                }
            }
        } else {
            $parts = $xpath->query("//part[@id='reference']");
            for ($i = 0; $i < $parts->length; $i++) {
                $node = $parts->item($i);
                foreach ($extrasChapters as $chapter) {
                    $new = $doc->importNode($chapter, true);
                    $node->appendChild($new);
                }
            }
        }
    }

    // Strip "index" node from source
    echo "    REMOVING 'index' node from manual source for language $lang\n";
    $book      = $doc->documentElement;
    $indexNode = $book->getElementsByTagName('index')->item(0);
    $book->removeChild($indexNode);
    $doc->save('manual.processed.xml');

    // Create TOC
    echo "    CREATING COMPONENT TOC for manual language $lang\n";
    $xpath = new DOMXPath($doc);
    if (version_compare($manVersion, '1.9.7', '<=')) {
        $chapters = $xpath->query('//chapter');
    } else {
        $chapters = $xpath->query('//part[@id="reference"]/chapter');
    }
    $components = array();
    for ($i = 0; $i < $chapters->length; $i++) {
        $chapter   = $chapters->item($i);
        $titleNode = $chapter->getElementsByTagName('title')->item(0);
        $title     = $titleNode->nodeValue;
        $chapterId = $chapter->attributes->getNamedItem('id')->nodeValue;
        $components[$chapterId] = $title;
    }
    // broke off start delimiter due to editor syntax highlighting
    $toc = '<' . "?php\necho \$this->components(" . var_export($components, 1) . ");\n";
    file_put_contents("$viewPath/$lang/_toc.phtml", $toc);

    // Docbook 4->5 conversion
    echo "    CONVERTING manual sources to DocBook 5\n";
    passthru("xsltproc --xinclude $phdPath/db4-upgrade.xsl manual.processed.xml > manual.db5.xml");

    // Build zfonline docs
    echo "    BUILDING online manual view scripts\n";
    passthru("$php $phdPath/pear/phd -g 'phpdotnet\phd\Highlighter_GeSHi' --xinclude -f zfonline -d manual.db5.xml");
    echo "        Rewriting image links\n";
    passthru("find output/zf-online-chunked-xhtml -name '*.phtml' -print | xargs sed -ri 's/((src|href)=\")(images\/)([^\"]*?\")/\\1\/images\/manual\/\\4/g'");

    // sync online docs to views
    echo "    SYNCING online manual view scripts to working directory\n";
    passthru("cp -a output/zf-online-chunked-xhtml/* $viewPath/$lang/");

    // Cleanup
    if ($cleanup) {
        echo "    CLEANING UP generated artifacts\n";
        unlink('./manual.processed.xml');
        unlink('./manual.db5.xml');
        recursiveDelete('./output');
    }

    // Return to working directory
    chdir(CWD);
}

// if $cleanup, remove all created directories
if ($cleanup) {
    echo "CLEANING UP...\n";
    echo "    MANUAL BUILD TOOL ARTIFACTS...\n";
    recursiveDelete($phdPath);
    if (!$zfSource) {
    echo "    MANUAL SOURCE TREE...\n";
        recursiveDelete($manPath);
    }
}

echo "DONE!\n\n";
echo "Generated view scripts are in $viewPath\n";
echo "Execute the following to move them into the sitewide source tree:\n\n";
echo "    rsync -aC --delete $viewPath {$config->manual->views}/manual/\n\n";
echo "Be sure to add and check them into the repository.\n";

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
