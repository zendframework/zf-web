<?php
error_reporting ( E_ALL );

require_once ('Zend/Amf/Server.php');
require_once ('QosPortalService.php');
require_once ('QosDataService.php');
require_once ('QosUserService.php');
require_once ('QosReportsService.php');
require_once ('QosCommentService.php');
require_once ('QosDbCheck.php');
require_once ('QosImportErrorService.php');
require_once ('QosExportService.php');

$server = new Zend_Amf_Server ( );

$server->setClass ( "QosPortalService" );
$server->setClass ( "QosDataService" );
$server->setClass ( "QosUserService" );
$server->setClass ( "QosReportsService" );
$server->setClass ( "QosCommentService" );
$server->setClass ( "QosDbCheck" );
$server->setClass ( "QosImportErrorService" );
$server->setClass ( "QosExportService" );

$server->setProduction ( false );

$server->setClassMap ( "VOUser", "VOUser" );
$server->setClassMap ( "VOReport", "VOReport" );
$server->setClassMap ( "VOCartesianChartReport", "VOCartesianChartReport" );
$server->setClassMap ( "VOTableReport", "VOTableReport" );
$server->setClassMap ( "VOMultiviewReport", "VOMultiviewReport" );
$server->setClassMap ( "VOCategory", "VOCategory" );
$server->setClassMap ( "VOReportType", "VOReportType" );
$server->setClassMap ( "VOKpi", "VOKpi" );
$server->setClassMap ( "VOKpiType", "VOKpiType" );
$server->setClassMap ( "VOKpiValues", "VOKpiValues" );
$server->setClassMap ( "VOLogicalReportGroup", "VOLogicalReportGroup" );
$server->setClassMap ( "VOLimit", "VOLimit" );
$server->setClassMap ( "VOComment", "VOComment" );
$server->setClassMap ( "VOKpiValue", "VOKpiValue" );
$server->setClassMap ( "VOLimitValue", "VOLimitValue" );
$server->setClassMap ( "VORunTimeError", "VORunTimeError" );
$server->setClassMap ( "VOImportError", "VOImportError" );

echo ($server->handle ());