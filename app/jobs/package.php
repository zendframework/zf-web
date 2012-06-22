<?php
/**
 * ARGUMENTS:
 *    --use-tag="website-release-X.Y.Z"      tag to use
 *    --create-tag="website-release-X.Y.Z"   tag to create (and use)
 *    --cleanup=0                            cleanup afterwards? (default: no)
 *    --tmp-dir=./build                      name of build directory
 *
 * One of --use-tag or --create-tag must be present
 *
 * ALL CASES
 * create tag if requested
 * export tag to temporary dir
 * copy app/etc/config.ini.dist config.ini
 * touch required cache directories
 * create symlinks to apidoc directories
 * get changelog data
 * process FAQ
 * create tarball from temporary tag dir
 * cleanup
 * 
 * ####################################################################
 * DEPLOYMENT (fw02)
 * ARGUMENTS:
 *    --package=filename                     Package file to use
 * 
 * unpack package
 * sync in jira/confluence indices
 * update RSS feeds
 * update symlink
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
define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ?: 'production'));
date_default_timezone_set('GMT');
ini_set('display_errors', false);
ini_set('memory_limit', -1);
error_reporting(E_ALL | E_STRICT);
set_time_limit(0);
$paths = array(
    '.',
    dirname(__FILE__) . '/../../framework/library',
    get_include_path(),
);
set_include_path(implode(PATH_SEPARATOR, $paths));
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();

// CONFIGURATION
define('CWD', realpath(getcwd()));
define('ZFBUILD_SVN', 'http://framework.zend.com/svn/framework/build-tools/trunk/build-tools');
define('ZFWEB_GIT', 
    (getenv('ZFWEB_GIT') ?: 'git@github.com:zendframework/zf-web.git')
);
define('ZFWEB_CONF_GIT', 
    (getenv('ZFWEB_CONF_GIT') ?: getenv('USER') . '@fw02:/var/local/framework/web_config')
);

$languages = array(
    'en', 'de', 'fr', 'ja', 'ru', 'zh',
);

$tag        = null;
$createTag  = false;
$buildDir   = './build';
$cleanup    = false;
$noPackage  = false;
$pretend    = false;
$noExtras   = false;

// Get request parameters
$opts = new Zend_Console_Getopt(array(
    'use-tag|u-s'      => 'Website release tag to use',
    'create-tag|c-s'   => 'Website release tag to create (and use)',
    'tmp-dir|t-s'      => 'Name of build directory to use (defaults to ./build)',
    'cleanup|x'        => 'Cleanup afterwards?',
    'no-package|n'     => 'Build docs, but do not build site package',
    'pretend|p'        => 'Pretend (do not do svn commits)',
));

try {
    $opts->parse();
} catch (Zend_Console_Getopt_Exception $e) {
    echo $e->getUsageMessage();
    exit;
}

if (!isset($opts->u) && !isset($opts->c)) {
    echo "MUST specify one of -u or -c\n\n";
    echo $opts->getUsageMessage();
    exit(42);
}

if (isset($opts->u) && isset($opts->c)) {
    echo "CONFLICT: MUST specify one of -u OR -c -- NOT BOTH\n\n";
    echo $opts->getUsageMessage();
    exit(42);
}

if (isset($opts->p)) {
    $pretend = true;
}

if (isset($opts->n)) {
    $noPackage = true;
}


if (isset($opts->u)) {
    $tag = $opts->u;
} elseif (isset($opts->c)) {
    $tag       = $opts->c;
    $createTag = true;
}
$svnTag = "website-release-$tag";

if (isset($opts->t)) {
    $buildDir = $opts->t;
}
if (!is_dir($buildDir)) {
    if (!mkdir($buildDir, 0777, true)) {
        echo "Invalid build directory provided ('$buildDir'), or cannot create\n\n";
        echo $opts->getUsageMessage();
        exit(42);
    }
}
$buildDir = realpath($buildDir);

if (isset($opts->b)) {
    $buildDocs = false;
}

if (isset($opts->x)) {
    $cleanup = true;
}

$config = new Zend_Config_Ini(dirname(__FILE__) . '/../etc/config.ini', 'production');

if ($pretend || $noPackage) {
    echo (($pretend) ? 'PRETEND' : '"NO PACKAGE"') . " OPTION ENABLED; ENDING SCRIPT";
    exit(0);
}

echo "CLONING website \n";
$pwd = getcwd();
chdir($buildDir);
passthru("git clone " . ZFWEB_GIT . " $svnTag", $failed);
if ($failed) {
    echo "FAILED CLONING website to $buildDir/$svnTag\n\n";
    exit(42);
}
chdir("$buildDir/$svnTag");

if ($createTag) {
    echo "CREATING website release tag $svnTag\n";
    passthru("git tag -s -m 'Creating tag for $tag release' $svnTag", $failed);
    if ($failed) {
        echo "FAILED CREATING TAG $svnTag for $tag\n\n";
        exit(42);
    }

    echo "PUSHING website release tag $svnTag\n";
    passthru("git push --tags origin", $failed);
    if ($failed) {
        echo "FAILED PUSHING website release tag $svnTag\n\n";
        exit(42);
    }
}

echo "CHECKING OUT website release tag $svnTag\n";
passthru("git checkout $svnTag", $failed);
if ($failed) {
    echo "FAILED CHECKOUT OUT website release tag $svnTag\n\n";
    exit(42);
}

echo "REMOVING .git directory\n";
recursiveDelete("./.git");

// Return to previous directory
chdir($pwd);

echo "RETRIEVING production config.ini\n";
passthru("git clone " . ZFWEB_CONF_GIT . " $buildDir/web_config", $failed);
if ($failed) {
    echo "FAILED RETRIEVING production config.ini\n\n";
    exit(42);
}
echo "COPYING config.ini to build directory\n";
copy(
    "$buildDir/web_config/config.ini",
    "$buildDir/$svnTag/app/etc/config.ini"
);

echo "CREATING SYMLINKS for API documentation\n";
$apiSourcePath  = $config->apidoc->source;
$apiPackagePath = $config->apidoc->package;
foreach ($config->apidoc->versions->toArray() as $minor => $mini) {
    chdir("$buildDir/$svnTag/document_root/apidoc");
    $minor = str_replace('r_', '', $minor);
    $minor = str_replace('_', '.', $minor);

    $sourcePath = sprintf($apiSourcePath, $mini);
    symlink($sourcePath, $minor);

    chdir("$buildDir/$svnTag/document_root/manual/apidoc");
    foreach (array('tar.gz', 'zip') as $ext) {
        $packagePath = sprintf($apiPackagePath, $mini, $mini, $ext);
        symlink($packagePath, basename($packagePath));
    }
}
chdir(CWD);

echo "CREATING SYMLINK for releases\n";
chdir("$buildDir/$svnTag/document_root");
symlink($config->releases->path, 'releases');
chdir(CWD);

/**
 * @todo make this configurable?
 */
echo "CREATING SYMLINK for code browser\n";
chdir("$buildDir/$svnTag/document_root");
symlink('/var/www/websvn', 'code');
chdir(CWD);

echo "FETCHING CHANGELOG data from JIRA\n";
passthru("php " . realpath(__DIR__) . "/getChangelogData.php");

echo "FETCHING FAQ data from CONFLUENCE\n";
passthru("php " . realpath(__DIR__) . "/processFaq.php");

echo "UPDATING PERMISSIONS on job scripts\n";
foreach (glob("$buildDir/$svnTag/app/jobs/*.sh") as $filename) {
    chmod($filename, 0755);
}

echo "CREATING TARBALL\n";
chdir($buildDir);
passthru("tar czf $svnTag.tar.gz $svnTag", $failed);
if ($failed) {
    echo "FAILED CREATING TARBALL for $svnTag\n\n";
    exit(42);
}
chdir(CWD);

if ($cleanup) {
    echo "CLEANING UP\n";
    recursiveDelete("$buildDir/$svnTag");
}

echo "DONE!\n";
echo "Package tarball is $buildDir/$svnTag.tar.gz\n";

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
