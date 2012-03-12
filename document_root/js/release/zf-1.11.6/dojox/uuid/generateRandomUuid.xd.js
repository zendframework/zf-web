/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.uuid.generateRandomUuid"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.uuid.generateRandomUuid"]){_4._hasResource["dojox.uuid.generateRandomUuid"]=true;_4.provide("dojox.uuid.generateRandomUuid");_6.uuid.generateRandomUuid=function(){var _7=16;function _8(){var _9=Math.floor((Math.random()%1)*Math.pow(2,32));var _a=_9.toString(_7);while(_a.length<8){_a="0"+_a;}return _a;};var _b="-";var _c="4";var _d="8";var a=_8();var b=_8();b=b.substring(0,4)+_b+_c+b.substring(5,8);var c=_8();c=_d+c.substring(1,4)+_b+c.substring(4,8);var d=_8();var _e=a+_b+b+_b+c+d;_e=_e.toLowerCase();return _e;};}}};});