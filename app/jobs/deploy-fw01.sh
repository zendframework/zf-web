#!/bin/sh

# Ensure we're on fw01
if [ `hostname` != "fw01" ];then
    echo "This script should only be run on fw01"
    exit 1
fi

# Check for www user
if [ ! $( id -u ) -eq 80 ]; then
    echo "You must be the 'www' user to run this script"
    exit 1
fi

# Check for tag and version in arguments
if [ $# -lt 2 ];then
    echo "Usage: `basename $0` <tagname> <version>"
    echo "where <tagname> is the name of the tag, minus any path information"
    echo "and <version> is the version being released"
    exit 1
fi

# Set some variables
TAG=$1
RELEASE=$2

# Sync artifacts
cd /var/local/framework
rsync -a fw02:/var/local/framework/ZendFramework-$RELEASE .
rsync -a fw02:/var/local/framework/working .
(cd manual && git fetch origin && git rebase origin/master)

# Sync releases and website
cd /var/www
rsync -a fw02:/var/www/$TAG .
rsync -a fw02:/var/www/releases .

# Re-point symlink
cd /var/www
rm website && ln -s $TAG website
