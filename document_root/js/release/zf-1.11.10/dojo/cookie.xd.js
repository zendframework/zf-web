/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.cookie"],["require","dojo.regexp"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.cookie"]){_4._hasResource["dojo.cookie"]=true;_4.provide("dojo.cookie");_4.require("dojo.regexp");_4.cookie=function(_7,_8,_9){var c=document.cookie;if(arguments.length==1){var _a=c.match(new RegExp("(?:^|; )"+_4.regexp.escapeString(_7)+"=([^;]*)"));return _a?decodeURIComponent(_a[1]):undefined;}else{_9=_9||{};var _b=_9.expires;if(typeof _b=="number"){var d=new Date();d.setTime(d.getTime()+_b*24*60*60*1000);_b=_9.expires=d;}if(_b&&_b.toUTCString){_9.expires=_b.toUTCString();}_8=encodeURIComponent(_8);var _c=_7+"="+_8,_d;for(_d in _9){_c+="; "+_d;var _e=_9[_d];if(_e!==true){_c+="="+_e;}}document.cookie=_c;}};_4.cookie.isSupported=function(){if(!("cookieEnabled" in navigator)){this("__djCookieTest__","CookiesAllowed");navigator.cookieEnabled=this("__djCookieTest__")=="CookiesAllowed";if(navigator.cookieEnabled){this("__djCookieTest__","",{expires:-1});}}return navigator.cookieEnabled;};}}};});