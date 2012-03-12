#!/bin/sh
# Check for root
if [ ! $( id -u ) -eq 0 ]; then
    echo "You must be root to run this script"
    exit 1
fi

cd /usr/local/apache/conf/extra
sed -ri 's/^(\s+)# (Proxy)/\1\2/' httpd-vhosts.conf
/etc/init.d/apache graceful
