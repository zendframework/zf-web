<?php
/**
 * zend_download_report.php
 * Copyright 2006-2007 Zend Technologies.
 *
 * Read Apache common log format.  Parse Zend Framework downloads, including
 * product releases, subversion access, and nightly snapshots.
 *
 * Note: inferring Subversion checkouts from logs is problematic.
 * The information in the log does not distinguish the request between
 * svn checkout, update, export, merge, or diff.  These operations all
 * appear the same in the log.
 *
 * Usage of this script:
 *
 * zend_download_report.php [ -v ] [ -f ] [ -s path ] directory ...
 *
 * -v enables verbose output.
 * -f forces logs to be re-parsed even if the aggregate data persisted file
 *    is present.  Also forces log for current date to be parsed.
 * -s specifies that a spreadsheet to be written to the given pathname.
 *    If the basename file is given as "DEFAULT", then the filename is
 *    "ZendFramework-DownloadReport-YYYYMMDD.xls", in the directory given in the path.
 *    E.g. "-s /tmp/DEFAULT" writes the spreadsheet at the path:
 *     "/tmp/ZendFramework-DownloadReport-YYYYMMDD.xls".
 *
 * The script searches the directories specified in the command-line
 * arguments for files called "access.log".  If such a file is found,
 * the script also looks for a file named "downloads.ser" in the same
 * subdirectory.
 *
 * If "downloads.ser" is present, the script reads that file and uses eval()
 * to un-persist, then merges the data into an array.
 *
 * If "downloads.ser" is not present, but "access.log" is, the script reads
 * the log file and counts Zend Framework downloads.  But the script does
 * not by default analyze a log file whose last modification date is the
 * same as the current date.
 *
 * The log file is also analyzed if the "-f" flag is given;
 * any "downloads.ser" file present in that subdirectory is overwritten.
 * This also causes the log file for the current day to be analyzed.
 *
 * The spreadsheet is constructed dynamically using the PEAR class
 * Spreadsheet_Excel_Writer.  See the following links for more information:
 * http://www.sitepoint.com/print/pear-spreadsheet_excel_writer
 * http://pear.php.net/manual/en/package.fileformats.spreadsheet-excel-writer.php
 */

class Zend_Apache_Log_DownloadCount
{

    /**
     * @var array
     */
    protected static $_monthNameToNumber = array(
        'Jan'       => 1,
        'January'   => 1,
        'Feb'       => 2,
        'February'  => 2,
        'Mar'       => 3,
        'March'     => 3,
        'Apr'       => 4,
        'April'     => 4,
        'May'       => 5,
        'Jun'       => 6,
        'June'      => 6,
        'Jul'       => 7,
        'July'      => 7,
        'Aug'       => 8,
        'August'    => 8,
        'Sep'       => 9,
        'September' => 9,
        'Oct'       => 10,
        'October'   => 10,
        'Nov'       => 11,
        'November'  => 11,
        'Dec'       => 12,
        'December'  => 12,
    );

    /**
     * @var array
     */
    protected static $_restrictedIPs = array(
        '10.220.223.46' => 'fw01',
        '10.220.223.47' => 'fw02',
        '127.0.0.1'     => 'localhost',
    );


    /**
     * @var int
     */
    protected $_urlNo = 1;

    /**
     * @var array
     */
    protected $_resources = array();

    /**
     * @var array
     */
    protected $_fullResourcesList = array();

    /**
     * @var array
     */
    protected $_logData = array();

    /**
     * @var array
     */
    protected $_format = array();

    /**
     * @var bool
     */
    protected $_fullData = true;

    /**
     * @var int
     */
    protected static $_bytesMin = 0;

    /**
     * @var array
     */
    protected static $_httpCodeOk = array(200, 206);

    /**
     * @param bool $fullData
     */
    public function __construct($fullData = true)
    {
        $this->_fullData = $fullData;
    }

    /**
     * @param Worksheet $worksheet
     * @return void
     */
    protected function writeSpreadsheetHeaders($worksheet)
    {
        $worksheet->write(0, 0, 'Date', $this->_format['header']);
        $worksheet->setColumn(0, 0, 10);
        foreach ($this->_resources as $resource => $col) {
            $worksheet->write(0, $col, $resource, $this->_format['header']);
            $worksheet->setColumn($col, $col, 11);
        }
        $worksheet->freezePanes(array(1, 1, 1, 1));
    }

    /**
     * @param Worksheet $worksheet
     * @param int $row
     * @param boolean $writeRightTotal
     * @return void
     */
    protected function writeSpreadsheetTotals($worksheet, $row, $writeRightTotal = true)
    {
        // Write lower left TOTAL label
        $worksheet->write($row, 0, 'TOTAL', $this->_format['totalBottom']);

        if ($writeRightTotal) {
            // Write upper right TOTAL header label
            $col = count($this->_resources)+1;
            $worksheet->write(0, $col, 'TOTAL', $this->_format['header']);
            $worksheet->setColumn($col, $col, 11);

            // Write end-of-row totals
            $col = count($this->_resources)+1;
            for ($i = 1; $i < $row; ++$i) {
                $cell1 = Spreadsheet_Excel_Writer::rowcolToCell($i, 1);
                $cell2 = Spreadsheet_Excel_Writer::rowcolToCell($i, $col-1);
                $worksheet->writeFormula($i, $col, "=SUM($cell1:$cell2)", $this->_format['totalRight']);
            }
        }

        // Write bottom-of-column totals
        foreach ($this->_resources as $resource => $col) {
            $cell1 = Spreadsheet_Excel_Writer::rowcolToCell(1, $col);
            $cell2 = Spreadsheet_Excel_Writer::rowcolToCell($row-1, $col);
            $worksheet->writeFormula($row, $col, "=SUM($cell1:$cell2)", $this->_format['totalBottom']);
        }

        if ($writeRightTotal) {
            // Calculate grand total
            $cell1 = Spreadsheet_Excel_Writer::rowcolToCell($row, 1);
            $cell2 = Spreadsheet_Excel_Writer::rowcolToCell($row, count($this->_resources));
            $col = count($this->_resources)+1;
            $worksheet->writeFormula($row, $col, "=SUM($cell1:$cell2)", $this->_format['grandTotal']);
        }
    }

    /**
     * @param int $year
     * @param int $month
     * @param int $day
     * @return int
     */
    protected function excelDate($year, $month, $day)
    {
        $ts = mktime(0, 0, 0, $month, $day, $year);
        $seconds_in_a_day = 86400;
        $ut_to_ed_diff = $seconds_in_a_day * 25569;
        return ($ts + $ut_to_ed_diff) / $seconds_in_a_day;
    }

    /**
     * @param string $workbookFilename
     * @param boolean $writeRightTotal
     * @return void
     */
    public function saveSpreadsheet($workbookFilename = 'downloadReport.xls', $writeRightTotal = true)
    {
        require_once('Spreadsheet/Excel/Writer.php');

        $workbook = new Spreadsheet_Excel_Writer($workbookFilename);
        $wsYear =& $workbook->addWorksheet('Downloads per Year');
        $wsMonth =& $workbook->addWorksheet('Downloads per Month');
        $wsMonth->activate(); // set to default worksheet when opened
        $wsDay =& $workbook->addWorksheet('Downloads per Day');
        if ($this->_fullData) {
            $wsFullData =& $workbook->addWorksheet('Full Data');
        }

        /**
         * Create formats
         */
        $this->_format['header'] =& $workbook->addFormat();
        $this->_format['header']->setBold();
        $this->_format['header']->setBottom(2);
        $this->_format['header']->setPattern();
        $this->_format['header']->setFgColor(18);
        $this->_format['header']->setColor('white');
        $this->_format['header']->setTextWrap();

        $this->_format['number'] =& $workbook->addFormat();
        $this->_format['number']->setNumFormat('#,##0');

        $this->_format['totalBottom'] =& $workbook->addFormat();
        $this->_format['totalBottom']->setBold();
        $this->_format['totalBottom']->setTop(2);
        $this->_format['totalBottom']->setNumFormat('#,##0');
        $this->_format['totalBottom']->setPattern();
        $this->_format['totalBottom']->setFgColor(22);

        $this->_format['totalRight'] =& $workbook->addFormat();
        $this->_format['totalRight']->setBold();
        $this->_format['totalRight']->setLeft(2);
        $this->_format['totalRight']->setNumFormat('#,##0');
        $this->_format['totalRight']->setPattern();
        $this->_format['totalRight']->setFgColor(22);

        $this->_format['grandTotal'] =& $workbook->addFormat();
        $this->_format['grandTotal']->setBold();
        $this->_format['grandTotal']->setBorder(2);
        $this->_format['grandTotal']->setNumFormat('#,##0');
        $this->_format['grandTotal']->setPattern();
        $this->_format['grandTotal']->setFgColor('cyan');

        $this->_format['date'] =& $workbook->addFormat();
        $this->_format['date']->setNumFormat('YYYY-MM-DD');

        $this->_format['month'] =& $workbook->addFormat();
        $this->_format['month']->setNumFormat('YYYY-MM');

        $this->_format['year'] =& $workbook->addFormat();
        $this->_format['year']->setNumFormat('YYYY');

        $dayRow = 0;
        $monthRow = 0;
        $totalRow = 0;
        $r = 1;

        // ksort($this->_resources);
        $resNames    = array();
        $resVersions = array();
        foreach ($this->_resources as $resKey => $value) {
            $patternIsFound = false;
            foreach ($this->_rexpr as $resName => $pattern) {
                if (($offset = strpos($resKey, $resName)) !== false) {
                    $resNames[]    = $resName;
                    $resVersions[] = substr($resKey, $offset + strlen($resName));
                    $patternIsFound = true;
                    break;
                }
            }
            if (!$patternIsFound) {
                $resNames[]    = $resKey;
                $resVersions[] = '0';
            }
        }
        uasort($resVersions, 'version_compare');               // Sort by version with storing original index in keys
        $resVersionsIDs = array_keys($resVersions);            // Get original IDs, sorted by version
        $resVersionSortIndexes = array_flip($resVersionsIDs);  // Store original IDs as keys and sorting
                                                               // index (0, 1, 2, ...) as values
        ksort($resVersionSortIndexes);                         // Restore original order (corresponding to $this->_resources
                                                               // and $resNames arrays) with calculated sorting index
        array_multisort($resNames, SORT_STRING, $resVersionSortIndexes, SORT_NUMERIC, $this->_resources);

        foreach (array_keys($this->_resources) as $resource) {
            $this->_resources[$resource] = $r++;
        }

        $this->writeSpreadsheetHeaders($wsYear);
        $this->writeSpreadsheetHeaders($wsMonth);
        $this->writeSpreadsheetHeaders($wsDay);
        if ($this->_fullData) {
            $this->writeSpreadsheetHeaders($wsFullData);
        }

        ++$totalRow;
        ++$monthRow;
        ++$dayRow;

        ksort($this->_logData);
        foreach ($this->_logData as $year => $monthArray) {
            $yearTotal = array();

            ksort($monthArray);
            foreach ($monthArray as $month => $dayArray) {
                $monthTotal = array();

                ksort($dayArray);
                foreach ($dayArray as $day => $resourceArray) {
                    /**
                     * Write date in day and fulldata worksheets
                     */
                    $wsDay->write($dayRow, 0,
                        $this->excelDate($year, $month, $day),
                        $this->_format['date']);
                    if ($this->_fullData) {
                        $wsFullData->write($dayRow, 0,
                            $this->excelDate($year, $month, $day),
                            $this->_format['date']);
                    }

                    /**
                     * Output totals per resource per day
                     */
                    foreach ($resourceArray as $resource => $ipArray) {
                        if (!isset($this->_resources[$resource])) {
                            continue;
                        }

                        $col = $this->_resources[$resource];

                        $count = count($ipArray);
                        $wsDay->write($dayRow, $col, $count, $this->_format['number']);

                        if ($this->_fullData) {
                            $sum = array_sum($ipArray);
                            $wsFullData->write($dayRow, $col, $sum, $this->_format['number']);
                        }

                        if (!isset($monthTotal[$resource])) {
                            $monthTotal[$resource] = $count;
                        } else {
                            $monthTotal[$resource] += $count;
                        }
                        if (!isset($yearTotal[$resource])) {
                            $yearTotal[$resource] = $count;
                        } else {
                            $yearTotal[$resource] += $count;
                        }
                    }

                    ++$dayRow;
                }

                /**
                 * Write date in month worksheet
                 */
                $wsMonth->write($monthRow, 0,
                    $this->excelDate($year, $month, 1),
                    $this->_format['month']);

                /**
                 * Write totals per resource per month
                 */
                foreach ($this->_resources as $resource => $col)
                {
                    if (!isset($monthTotal[$resource])) {
                        $monthTotal[$resource] = null;
                    }

                    $wsMonth->write($monthRow, $col, $monthTotal[$resource], $this->_format['number']);
                }

                ++$monthRow;
            }

            /**
             * Write date in year worksheet
             */
            $wsYear->write($totalRow, 0,
                $this->excelDate($year, 1, 1),
                $this->_format['year']);

            /**
             * Write totals per resource per year
             */
            foreach ($this->_resources as $resource => $col) {
                if (!isset($yearTotal[$resource])) {
                    $yearTotal[$resource] = null;
                }

                $wsYear->write($totalRow, $col, $yearTotal[$resource], $this->_format['number']);
            }

            ++$totalRow;
        }

        $this->writeSpreadsheetTotals($wsYear, $totalRow, $writeRightTotal);
        $this->writeSpreadsheetTotals($wsMonth, $monthRow, $writeRightTotal);
        $this->writeSpreadsheetTotals($wsDay, $dayRow, $writeRightTotal);
        if ($this->_fullData) {
            $this->writeSpreadsheetTotals($wsFullData, $dayRow, $writeRightTotal);
        }

        $workbook->close();
    }

    /**
     * @var array
     */
    protected $_rexpr = array(
        'Framework Snapshots' => '^/snapshots/ZendFramework',
        'Framework'           => '^/releases/ZendFramework-(\d+.\d+)',
        'Gdata'               => '^/releases/ZendG[Dd]ata-(\d+.\d+)',
        'Infocard'            => '^/releases/ZendInfo[Cc]ard-(\d+.\d+)',
        'AMF'            => '^/releases/Zend[Aa][Mm][Ff]-(\d+.\d+)',
        'Framework SVN'       => array('^/svn/framework/!svn/vcc/default', 'REPORT')
    );

    /**
     * @param array $log
     * @return string
     */
    private function getResource($log)
    {
        foreach ($this->_rexpr as $label => $pattern) {
            if (is_array($pattern)) {
                $method = $pattern[1];
                if ($log['method'] != $method) {
                    continue;
                }
                $pattern = $pattern[0];
            }
            if (preg_match("|$pattern|", $log['url'], $matches)) {
                array_shift($matches);
                foreach ($matches as $arg) {
                    $label .= ' ' . $arg;
                }
                return $label;
            }
        }
        return null;
    }

    /**
     * @param array $log
     * @return void
     */
    public function processLogEntry($log)
    {
        if (is_numeric($log['bytes'])  &&  $log['bytes'] <= self::$_bytesMin) {
            return;
        }
        if (!in_array($log['code'], self::$_httpCodeOk)) {
            return;
        }
        $resource = $this->getResource($log);
        if ($resource) {
            $this->_resources[$resource] = true;
            if (!isset($this->_logData[ $log['year'] ][ $log['month'] ][ $log['day'] ][ $resource ][ $log['ip'] ])) {
                $this->_logData[ $log['year'] ][ $log['month'] ][ $log['day'] ][ $resource ][ $log['ip'] ] = 1;
            } else {
                $this->_logData[ $log['year'] ][ $log['month'] ][ $log['day'] ][ $resource ][ $log['ip'] ]++;
            }
        }
    }

    /**
     * @param resource $resource
     * @return void
     */
    public function processLogStream($resource)
    {
        while (!feof($resource)) {
            $buffer = fgets($resource);
            if ($buffer === false) {
                continue;
            }
            $buffer = substr($buffer, 0, -1); // remove \n
            /**
             * The following assumes Apache common log format
             * If you change to combined or other log format,
             * this code needs to be adapted.
             */
            $logData = explode(' ', $buffer, 10);
            if (count($logData) < 10) {
                continue;
            }

            list($log['ip'],
                $log['user'],
                $log['vhost'],
                $log['datetime'],
                $log['tz'],
                $log['method'],
                $log['url'],
                $log['http'],
                $log['code'],
                $log['bytes']) = $logData;
            $log['datetime'] = substr($log['datetime'], 1); // remove [
            $log['tz']       = substr($log['tz'], 0, -1);   // remove ]
            $log['method']   = substr($log['method'], 1);   // remove "
            $log['http']     = substr($log['http'], 0, -1); // remove "
            list($date,
                $log['hour'],
                $log['minute'],
                $log['second']) = explode(':', $log['datetime']);
            list($log['day'],
                $log['month'],
                $log['year']) = explode('/', $date);
            $log['month'] = self::$_monthNameToNumber[$log['month']];

            $this->processLogEntry($log);
        }
    }

    /**
     * @param string $filename
     * @return void
     */
    public function processLogFile($filename)
    {
        $logFile = fopen($filename, 'r');
        if (!$logFile) {
            die($filename);
        }

        $this->processLogStream($logFile);

        fclose($logFile);
    }

    /**
     * @param string $filename
     * @return void
     */
    public function processLogArchive($filename)
    {
        $logFile = popen('bunzip2 -cdkq ' . escapeshellarg($filename), 'r');
        if (!$logFile) {
            die($filename);
        }

        $this->processLogStream($logFile);

        pclose($logFile);
    }


    /**
     * @param string $filename
     * @return void
     */
    public function saveData($filename)
    {
        $freeze = var_export($this->_logData, true);
        if (!($fp = fopen($filename, 'w'))) {
            die("cannot open $filename");
        }
        if (fwrite($fp, $freeze) == false) {
            die("cannot write to $filename");
        }
        fclose($fp);
    }

    /**
     * @param string $filename
     * @return void
     */
    public function loadData($filename)
    {
        if (!($fp = fopen($filename, 'r'))) {
            die("cannot open $filename");
        }
        if (($thaw = fread($fp, filesize($filename))) == false) {
            die("cannot read $filename");
        }
        fclose($fp);
        eval('$data = ' . $thaw . ';');
        foreach ($data as $year => $monthArray) {
            foreach ($monthArray as $month => $dayArray) {
                foreach ($dayArray as $day => $resourceArray) {
                    foreach ($resourceArray as $resource => $ipArray) {
                        $this->_resources[$resource] = 1;
                        foreach ($ipArray as $ip => $count) {
                            if (isset(self::$_restrictedIPs[$ip])) {
                                continue;
                            }

                            if (!isset($this->_logData[ $year ][ $month ][ $day ][ $resource ][ $ip ])) {
                                $this->_logData[ $year ][ $month ][ $day ][ $resource ][ $ip ] = $count;
                            } else {
                                $this->_logData[ $year ][ $month ][ $day ][ $resource ][ $ip ] += $count;
                            }
                        }
                    }
                }
            }
        }

        $this->_fullResourcesList = $this->_resources;
    }

    /*
     * @param array|null $includeFilter   If parameter is set it contains resources list allowed to be included into report
     * @param array|null $excludeFilter   If $includeFilter is null and this parameter is not null it contains
     *                                    respurces list to exclude from the report
     * @return void
     */
    public function setResourcesFilter($includeFilter = null, $excludeFilter = null)
    {
        if ($includeFilter !== null) {
            $this->_resources = array();

            foreach ($this->_fullResourcesList as $resKey => $resValue) {
                if (in_array($resKey, $includeFilter)) {
                    $this->_resources[$resKey] = $resValue;
                }
            }
        } else if ($excludeFilter !== null) {
            $this->_resources = $this->_fullResourcesList;

            foreach ($excludeFilter as $resource) {
                unset($this->_resources[$resource]);
            }
        } else {
            $this->clearResourcesFilter();
        }
    }

    /*
     * @return void
     */
    public function clearResourcesFilter($includeFilter = null, $excludeFilter = null)
    {
        $this->_resources = $this->_fullResourcesList;
    }

    /**
     * @return void
     */
    public function clearData()
    {
        $this->_logData = array();
    }

}

/**
 * @param string $directory
 * @param bool $recursive
 * @return array
 */
function directoryToArray($directory, $recursive)
{
    $array_items = array();
    if ($handle = opendir($directory)) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (is_dir($directory. "/" . $file)) {
                    if($recursive) {
                        $array_items = array_merge($array_items, directoryToArray($directory. "/" . $file, $recursive));
                    }
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                } else {
                    $file = $directory . "/" . $file;
                    $array_items[] = preg_replace("/\/\//si", "/", $file);
                }
            }
        }
        closedir($handle);
    }
    return $array_items;
}

define('APPLICATION_PATH', dirname(dirname(__FILE__)));
ini_set('display_errors', false);
$argv = $_SERVER['argv'];
array_shift($argv);

$force = false;
$verbose = false;
$spreadsheet = false;
while (!empty($argv)) {
    switch ($argv[0]) {
    case '-f':
    case '--force':
        $force = true;
        array_shift($argv);
        break;
    case '-s':
    case '--spreadsheet':
        $spreadsheet    = $argv[1];
        $spreadsheetSVN = $argv[2];
        array_shift($argv);
        array_shift($argv);
        array_shift($argv);
        break;
    case '-v':
    case '--verbose':
        $verbose = true;
        array_shift($argv);
        break;
    default:
        break 2;
    }
}

if (empty($argv)) {
    array_push($argv, '.');
}

$files = array();
foreach ($argv as $pathname) {
    if (is_dir($pathname)) {
        $files = array_merge($files, directoryToArray($pathname, true));
    } else if (file_exists($pathname)) {
        $files[] = $pathname;
    } else {
        die("Invalid file '$pathname' given");
    }
}

sort($files);

# Pass 1: parse log files and save serialized data
$parser = new Zend_Apache_Log_DownloadCount();
foreach ($files as $pathname) {
    if (basename($pathname) == 'access.log' ||
        basename($pathname) == 'access.log.bz2') {
        $statfile = dirname($pathname) . '/' . 'downloads.ser';
        $logStat = stat($pathname);
        $lastMod = date('Ymd', $logStat[9]);
        $today = date('Ymd');

        if ($force || (!is_readable($statfile) && $lastMod < $today)) {
            if ($verbose) {
                echo "Reading $pathname... ";
            }
            $parser->clearData();
            switch (basename($pathname)) {
                case 'access.log':
                    $parser->processLogFile($pathname);
                    break;

                case 'access.log.bz2':
                    $parser->processLogArchive($pathname);
                    break;
            }

            if ($verbose) {
                echo "done.\n";
                echo "Saving  $statfile... ";
            }
            $parser->saveData($statfile);
            if ($verbose) {
                echo "done.\n";
            }
        }

        if (basename($pathname) == 'access.log' && $lastMod < $today) {
            // Archive 'access.log' file if it wasn't done at previous step
            exec('bzip2 ' . escapeshellarg($pathname));
        }
    } else if (basename($pathname) == 'error.log') {
        $logStat = stat($pathname);
        $lastMod = date('Ymd', $logStat[9]);
        $today = date('Ymd');

        if ($lastMod < $today) {
            exec('bzip2 ' . escapeshellarg($pathname));
        }
    }
}

# Pass 2: load serialized data and make report
$report = new Zend_Apache_Log_DownloadCount();
if ($spreadsheet) {
    foreach ($files as $pathname) {
        if (basename($pathname) == 'downloads.ser') {
            if (is_readable($pathname)) {
                if ($verbose) {
                    echo "Loading $pathname... ";
                }
                $report->loadData($pathname);
                if ($verbose) {
                    echo "done.\n";
                }
            }
        }
    }
    if (basename($spreadsheet) == 'DEFAULT') {
        $spreadsheet    = dirname($spreadsheet) . '/ZendFramework-DownloadReport-' . date('Ymd') . '.xls';
        $spreadsheetSVN = dirname($spreadsheet) . '/ZendFrameworkSVN-DownloadReport-' . date('Ymd') . '.xls';
    }

    if ($verbose) {
        echo "Saving spreadsheet $spreadsheet... ";
    }
    $report->setResourcesFilter(null, array('Framework SVN'));
    $report->saveSpreadsheet($spreadsheet);

    if ($verbose) {
        echo "done.\n";
    }

    if ($verbose) {
        echo "Saving spreadsheet $spreadsheetSVN... ";
    }
    $report->setResourcesFilter(array('Framework SVN'));
    $report->saveSpreadsheet($spreadsheetSVN, false);

    if ($verbose) {
        echo "done.\n";
    }
}
