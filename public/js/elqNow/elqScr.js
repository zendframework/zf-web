// Copyright Eloqua Corporation.
var elqDt = new Date();
var elqMs = elqDt.getMilliseconds();
if ((typeof elqCurE != 'undefined') && (typeof elqPPS != 'undefined')){document.write('<SCR' + 'IPT TYPE="text/javascript" LANGUAGE="JavaScript" SRC="' + elqCurE + '?pps=' + elqPPS + '&siteid=' + elqSiteID + '&ref=' + elqReplace(elqReplace(location.href,'&','%26'),'#','%23') + '&ms=' + elqMs + '"><\/SCR' + 'IPT>');}
