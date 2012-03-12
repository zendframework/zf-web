/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.analytics._base"],["provide","dojox.analytics.plugins.consoleMessages"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.analytics.plugins.consoleMessages"]){_4._hasResource["dojox.analytics.plugins.consoleMessages"]=true;_4.require("dojox.analytics._base");_4.provide("dojox.analytics.plugins.consoleMessages");_6.analytics.plugins.consoleMessages=new (function(){this.addData=_4.hitch(_6.analytics,"addData","consoleMessages");var _7=_4.config["consoleLogFuncs"]||["error","warn","info","rlog"];if(!console){console={};}for(var i=0;i<_7.length;i++){if(console[_7[i]]){_4.connect(console,_7[i],_4.hitch(this,"addData",_7[i]));}else{console[_7[i]]=_4.hitch(this,"addData",_7[i]);}}})();}}};});