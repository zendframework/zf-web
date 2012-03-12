#!/bin/sh
if [ ! $( id -u ) -eq 80 ]; then
    echo "You must be the 'www' user to run this script"
    exit 1
fi

BASEDIR=`dirname $0`/../cache
CACHE="content"

if [ $# -ge 1 ];then
    CACHE=$1
fi

if [ ! -d "$BASEDIR/$CACHE" ];then
    echo "Specified cache directory does not exist"
    exit 1
fi

echo "Refreshing $CACHE..."
(cd $BASEDIR && mv "$BASEDIR/$CACHE" "$BASEDIR/tmp" && mkdir "$BASEDIR/$CACHE" && rm -Rf "$BASEDIR/tmp")
echo "[DONE]"
