// Copyright Eloqua Corporation.
var elqWDt = new Date(20020101);
var elqDt = new Date();
var elqMs = elqDt.getMilliseconds();
var elqTzo = elqWDt.getTimezoneOffset();
var elqRef2 = '';
if (typeof elqCurE != 'undefined'){
if (document.referrer) { elqRef2 = document.referrer; }
if ((typeof elqRef2 == 'undefined') || (elqRef2 == 'undefined') || (elqRef2 == '')) { elqRef2 = 'elqNone'; }
else { elqRef2 = elqReplace(elqReplace(elqRef2,'&','%26'),'#','%23'); }
document.write('<SCR' + 'IPT TYPE="text/javascript" LANGUAGE="JavaScript" SRC="' + elqCurE + '?pps=8&siteid=' + elqSiteID + '&ref=' + elqReplace(elqReplace(location.href,'&','%26'),'#','%23') + '&ref2=' + elqRef2 + '&tzo=' + elqTzo + '&ms=' + elqMs + '"><\/SCR' + 'IPT>');
}
