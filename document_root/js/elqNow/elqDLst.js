// Copyright Eloqua Corporation.
//
// Domain Checking Settings
//
// The domain checking functionality explicitly checks the domain of every redirect, which uses the Eloqua system.
// If the domain resides in the safe list, the redirect will happen as expected, but if the domain is not found, the
// user is redirected to a page which informs them that the link could not be verified as safe.
// 
// Required:
// To enable this functionality, the variable elqIC must be set to true, and a list of safe domains must be set in the
// variable elqDL (separated by the # symbol).
//
// Optional:
// If you wish to change the page that the user is redirected to upon finding an unsafe domain, you may specify a
// fully qualified URL in the variable elqDURL.  See example below.
//
// Testing:
// To test that everything is working properly, you may use the links below. To ensure a proper test, replace www.mydomain.com
// in the first link with your main domain. The first one should redirect you to your default homepage, and the second one should
// redirect you to the default unsafe page.
//
// http://www.mydomain.com/elqNow/elqRedir.htm?ref=http://www.mydomain.com
// http://www.mydomain.com/elqNow/elqRedir.htm?ref=http://www.unsafedomain.com/somefile.doc
// 
//
// This variable enables domain checking, if it is set to true domain checking will be enabled, conversely if it is false
// domain checking will be disabled.
// Example : var elqIC = true;
// Default : var elqIC = false;
var elqIC = false;

// This variable holds the list of safe domains.  Each domain must be separated by the pound (#) symbol and does 
// not require a preceding http:// or https://.  Ensure the each safe domain is listed explicitly.  Adding only
// mysite.com does NOT mean that www.mysite.com or secure.mysite.com is included.  Note that wild cards are NOT supported.
// Example:  var elqDL = 'www.mysite.com#mysite.com#secure.mysite.com';
// Default:  var elqDL = '';
var elqDL = '';

// This variable holds the URL of the page which the user gets redirected if the redirect is found to be unsafe.
// If you wish to change it from the default, be sure to ensure the URL is fully qualified.
// Example: var elqDURL = 'http://www.mysite.com/UnsafeRedirect.htm';
// Default: var elqDURL = 'elqNoRedir.htm';
var elqDURL = 'elqNoRedir.htm';