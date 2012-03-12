#!/bin/sh

: ${PHP:="/usr/local/bin/php"}
: ${DOWNLOAD_REPORT_PHP:="/var/www/website/app/jobs/zend_download_report.php"}
: ${FRAMEWORK_LOG_DIR:="/usr/local/apache/logs/framework"}

${PHP} -d "memory_limit=-1" "${DOWNLOAD_REPORT_PHP}" "${FRAMEWORK_LOG_DIR}"
