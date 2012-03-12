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
if (navigator.appName == 'Netscape' || navigator.userAgent.indexOf("Opera")!=-1) { document.write('<la' + 'yer hidden=true><im' + 'g src="' + elqCurE + '?pps=3&siteid=' + elqSiteID + '&ref2=' + elqRef2 + '&tzo=' + elqTzo + '&ms=' + elqMs + '" border=0 width=1 height=1 ><\/la' + 'yer>');}
else { document.write('<im' + 'g style="display:none" src="' + elqCurE + '?pps=3&siteid=' + elqSiteID + '&ref2=' + elqRef2 + '&tzo=' + elqTzo + '&ms=' + elqMs + '" border=0 width=1 height=1 >');}
}
