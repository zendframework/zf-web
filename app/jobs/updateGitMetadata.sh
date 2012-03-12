#!/bin/sh
if [ ! $( id -u ) -eq 80 ]; then
    echo "You must be the 'www' user to run this script"
    exit 1
fi

BINDIR=`dirname $0`
if [ -n "$(git --git-dir=/var/local/framework/git/zf2/.git fetch --all --prune 2>&1 | grep -v Fetching)" ];then
    $BINDIR/clearContentCache.sh zfstatus
    $BINDIR/clearContentCache.sh content
fi
