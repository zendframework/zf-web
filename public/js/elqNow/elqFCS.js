// Copyright Eloqua Corporation.
if (elqTryI){ document.write('<ifr' + 'ame name="elqFCSFra" style="visibility:none" src="/elqNow/elqBlank.htm" width=1 height=1 frameborder=0 border=0 NORESIZE SCROLLING="no"><\/ifr' + 'ame>');}
function elqFCS(strURL) {
 var elqRet = -1;
 var elqRef = strURL;
 if ((typeof elqRef == 'undefined') || (elqRef == 'undefined') || (elqRef == '')) { elqRef = 'elqNone'; }
 if (elqRef == 'elqNone') { elqRet = -2; }
 else {
  elqRef = elqReplace(elqReplace(elqRef,'&','%26'),'#','%23');
  var elqWDt = new Date(20020101);
  var elqDt = new Date();
  var elqMs = elqDt.getMilliseconds();
  var elqTzo = elqWDt.getTimezoneOffset();
  var elqRef2 = '';
  if (typeof elqCurE == 'undefined'){ elqRet = -3; }
  else {   
   if (document.referrer) { elqRef2 = document.referrer; }
   if ((typeof elqRef2 == 'undefined') || (elqRef2 == 'undefined') || (elqRef2 == '')) { elqRef2 = 'elqNone'; }
   else { elqRef2 = elqReplace(elqReplace(elqRef2,'&','%26'),'#','%23'); }
   if (elqTryI) {
    if (window.frames.elqFCSFra) { elqRet = 0;
     if (document.images) { window.frames.elqFCSFra.location.replace(elqCurE + '?pps=31&siteid=' + elqSiteID + '&ref=' + elqRef + '&ref2=' + elqRef2 + '&tzo=' + elqTzo + '&ms=' + elqMs); }
     else { window.frames.elqFCSFra.location.href = elqCurE + '?pps=31&siteid=' + elqSiteID + '&ref=' + elqRef + '&ref2=' + elqRef2 + '&tzo=' + elqTzo + '&ms=' + elqMs; }}
    else { elqRet = -5; }}
   else { elqRet = -4; }}}
 return elqRet;
}



