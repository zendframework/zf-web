#!/bin/sh

: ${PHP:="/usr/local/bin/php"}
: ${HOSTNAME=`hostname`}
: ${DOWNLOAD_REPORT_PHP:="/var/www/website/app/jobs/zend_download_report.php"}
: ${FRAMEWORK_LOG_DIR_FW01:="/usr/local/apache/logs/framework"}
: ${FRAMEWORK_LOG_DIR_FW02:="/usr/local/apache/logs/framework_fw02"}
: ${FRAMEWORK_LOG_DIR_FW02_SRC:="fw02:/usr/local/apache/logs/framework/"}
: ${REPORT_SPREADSHEET:="/tmp/ZendFramework-DownloadReport-`date +%F`.xls"}
: ${REPORT_SPREADSHEET_SVN:="/tmp/ZendFrameworkSVN-DownloadReport-`date +%F`.xls"}
: ${REPORT_ZIP:="/tmp/ZendFramework-DownloadReport-`date +%F`.zip"}
: ${RSYNC:="/usr/bin/rsync"}
: ${ZIP:="/usr/bin/zip"}
: ${MUTT:="/usr/bin/mutt"}
: ${MAIL_MESSAGE:="Zend Framework downloads report: `date +%F`"}
: ${MAIL_RECIPIENT_LIST:="andi@zend.com elaine@zend.com ifat@zend.com kent@zend.com pratibha.j@zend.com stephanie.d@zend.com michael.s@zend.com zeev@zend.com matthew@zend.com"}

${RSYNC} -a --include="*/" --include="downloads.ser" --exclude="*" "${FRAMEWORK_LOG_DIR_FW02_SRC}" "${FRAMEWORK_LOG_DIR_FW02}"

${PHP} -d "memory_limit=-1" "${DOWNLOAD_REPORT_PHP}" "${FRAMEWORK_LOG_DIR_FW01}" "${FRAMEWORK_LOG_DIR_FW02}"
${PHP} -d "memory_limit=-1" "${DOWNLOAD_REPORT_PHP}" -s "${REPORT_SPREADSHEET}" "${REPORT_SPREADSHEET_SVN}" "${FRAMEWORK_LOG_DIR_FW01}" "${FRAMEWORK_LOG_DIR_FW02}"
${ZIP} -qj "${REPORT_ZIP}" "${REPORT_SPREADSHEET}" "${REPORT_SPREADSHEET_SVN}"

echo "${MAIL_MESSAGE}" | ${MUTT} -s "${MAIL_MESSAGE}" -a "${REPORT_ZIP}" ${MAIL_RECIPIENT_LIST}

/bin/rm -f "${REPORT_SPREADSHEET}" "${REPORT_SPREADSHEET_SVN}" "${REPORT_ZIP}"
