<?php

require_once 'Zend/Db.php';
$oDatabase = Zend_Db::factory('PDO_MYSQL', array(
    'hostname' => 'localhost',
    'username' => 'root',
    'password' => '********',
    'dbname'   => 'test'
));
$oProfiler = $oDatabase->getProfiler();
$oProfiler->setEnabled(true);
$aLimits = array(1, 10, 50, 100, 250, 500, 750, 1000, 2500, 5000, 7500, 10000, 25000, 50000);
$oDatabase->query('SELECT history_id FROM email_history LIMIT 1');
$oProfiler->clear();

echo "\n\n";
foreach (array('history', 'history_072008') as $sTable)
{
    foreach ($aLimits as $iLimit) {
        $oDatabase->query('SELECT * FROM ' . $sTable . ' LIMIT ' . $iLimit);
    }

    $iTotalTime    = $oProfiler->getTotalElapsedSecs();
    $iQueryCount   = $oProfiler->getTotalNumQueries();
    $iLongestTime  = 0;
    $iLongestQuery = null;

    foreach ($oProfiler->getQueryProfiles() as $oQuery)
    {
        if ($oQuery->getElapsedSecs() > $iLongestTime)
        {
            $iLongestTime  = $oQuery->getElapsedSecs();
            $iLongestQuery = $oQuery->getQuery();
        }
    }

    echo '             Executed: ' . $iQueryCount . ' queries in ' . $iTotalTime . ' seconds' . "\n";
    echo ' Average query length: ' . $iTotalTime / $iQueryCount . ' seconds' . "\n";
    echo '   Queries per second: ' . $iQueryCount / $iTotalTime . "\n";
    echo ' Longest query length: ' . $iLongestTime . "\n";
    echo '        Longest query: ' . $iLongestQuery . "\n\n";
    
    $oProfiler->clear();
}