/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.validate.web"],["require","dojox.validate._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.validate.web"]){_4._hasResource["dojox.validate.web"]=true;_4.provide("dojox.validate.web");_4.require("dojox.validate._base");_6.validate.isIpAddress=function(_7,_8){var re=new RegExp("^"+_6.validate.regexp.ipAddress(_8)+"$","i");return re.test(_7);};_6.validate.isUrl=function(_9,_a){var re=new RegExp("^"+_6.validate.regexp.url(_a)+"$","i");return re.test(_9);};_6.validate.isEmailAddress=function(_b,_c){var re=new RegExp("^"+_6.validate.regexp.emailAddress(_c)+"$","i");return re.test(_b);};_6.validate.isEmailAddressList=function(_d,_e){var re=new RegExp("^"+_6.validate.regexp.emailAddressList(_e)+"$","i");return re.test(_d);};_6.validate.getEmailAddressList=function(_f,_10){if(!_10){_10={};}if(!_10.listSeparator){_10.listSeparator="\\s;,";}if(_6.validate.isEmailAddressList(_f,_10)){return _f.split(new RegExp("\\s*["+_10.listSeparator+"]\\s*"));}return [];};}}};});