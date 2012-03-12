#!/bin/sh
# Check for root
if [ ! $( id -u ) -eq 0 ]; then
    echo "You must be root to run this script"
    exit 1
fi


if [ -e /usr/local/jira/.jira-home.lock ];then
    echo "JIRA lock found; removing and restarting JIRA"
    rm /usr/local/jira/.jira-home.lock
    /etc/init.d/jira restart
fi
