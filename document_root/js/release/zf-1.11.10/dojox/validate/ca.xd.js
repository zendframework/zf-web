/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.validate.ca"],["require","dojox.validate._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.validate.ca"]){_4._hasResource["dojox.validate.ca"]=true;_4.provide("dojox.validate.ca");_4.require("dojox.validate._base");_4.mixin(_6.validate.ca,{isPhoneNumber:function(_7){return _6.validate.us.isPhoneNumber(_7);},isProvince:function(_8){var re=new RegExp("^"+_6.validate.regexp.ca.province()+"$","i");return re.test(_8);},isSocialInsuranceNumber:function(_9){var _a={format:["###-###-###","### ### ###","#########"]};return _6.validate.isNumberFormat(_9,_a);},isPostalCode:function(_b){var re=new RegExp("^"+_6.validate.regexp.ca.postalCode()+"$","i");return re.test(_b);}});}}};});