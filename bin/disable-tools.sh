#!/bin/sh
# Check for root
if [ ! $( id -u ) -eq 0 ]; then
    echo "You must be root to run this script"
    exit 1
fi

cd /usr/local/apache/conf/extra
perl -pi -e 's/^(\s+)(Proxy)/$1# $2/s' httpd-vhosts.conf
perl -pi -e 's/^(\s+)# (ProxyPass(Reverse)? \/svn)/$1$2/s' httpd-vhosts.conf
/etc/init.d/apache graceful
