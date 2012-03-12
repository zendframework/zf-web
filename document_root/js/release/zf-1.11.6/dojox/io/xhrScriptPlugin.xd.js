/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.io.xhrScriptPlugin"],["require","dojox.io.xhrPlugins"],["require","dojo.io.script"],["require","dojox.io.scriptFrame"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.io.xhrScriptPlugin"]){_4._hasResource["dojox.io.xhrScriptPlugin"]=true;_4.provide("dojox.io.xhrScriptPlugin");_4.require("dojox.io.xhrPlugins");_4.require("dojo.io.script");_4.require("dojox.io.scriptFrame");_6.io.xhrScriptPlugin=function(_7,_8,_9){_6.io.xhrPlugins.register("script",function(_a,_b){return _b.sync!==true&&(_a=="GET"||_9)&&(_b.url.substring(0,_7.length)==_7);},function(_c,_d,_e){var _f=function(){_d.callbackParamName=_8;if(_4.body()){_d.frameDoc="frame"+Math.random();}return _4.io.script.get(_d);};return (_9?_9(_f,true):_f)(_c,_d,_e);});};}}};});