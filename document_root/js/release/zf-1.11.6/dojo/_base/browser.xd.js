/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo._base.browser"],["require","dojo._base.window"],["require","dojo._base.connect"],["require","dojo._base.event"],["require","dojo._base.html"],["require","dojo._base.NodeList"],["require","dojo._base.query"],["require","dojo._base.xhr"],["require","dojo._base.fx"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo._base.browser"]){_4._hasResource["dojo._base.browser"]=true;_4.provide("dojo._base.browser");_4.require("dojo._base.window");_4.require("dojo._base.connect");_4.require("dojo._base.event");_4.require("dojo._base.html");_4.require("dojo._base.NodeList");_4.require("dojo._base.query");_4.require("dojo._base.xhr");_4.require("dojo._base.fx");_4.forEach(_4.config.require,function(i){_4["require"](i);});}}};});