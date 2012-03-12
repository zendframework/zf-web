/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.analytics._base"],["provide","dojox.analytics.plugins.window"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.analytics.plugins.window"]){_4._hasResource["dojox.analytics.plugins.window"]=true;_4.require("dojox.analytics._base");_4.provide("dojox.analytics.plugins.window");_6.analytics.plugins.window=new (function(){this.addData=_4.hitch(_6.analytics,"addData","window");this.windowConnects=_4.config["windowConnects"]||["open","onerror"];for(var i=0;i<this.windowConnects.length;i++){_4.connect(window,this.windowConnects[i],_4.hitch(this,"addData",this.windowConnects[i]));}_4.addOnLoad(_4.hitch(this,function(){var _7={};for(var i in window){if(_4.isObject(window[i])){switch(i){case "location":case "console":_7[i]=window[i];break;default:break;}}else{_7[i]=window[i];}}this.addData(_7);}));})();}}};});