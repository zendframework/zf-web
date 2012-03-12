/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.timing.doLater"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.timing.doLater"]){_4._hasResource["dojox.timing.doLater"]=true;_4.provide("dojox.timing.doLater");_4.experimental("dojox.timing.doLater");_6.timing.doLater=function(_7,_8,_9){if(_7){return false;}var _a=_6.timing.doLater.caller,_b=_6.timing.doLater.caller.arguments;_9=_9||100;_8=_8||_4.global;setTimeout(function(){_a.apply(_8,_b);},_9);return true;};}}};});