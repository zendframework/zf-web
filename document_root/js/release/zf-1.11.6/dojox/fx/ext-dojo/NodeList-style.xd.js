/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.fx.ext-dojo.NodeList-style"],["require","dojo.NodeList-fx"],["require","dojox.fx.style"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.fx.ext-dojo.NodeList-style"]){_4._hasResource["dojox.fx.ext-dojo.NodeList-style"]=true;_4.provide("dojox.fx.ext-dojo.NodeList-style");_4.experimental("dojox.fx.ext-dojo.NodeList-style");_4.require("dojo.NodeList-fx");_4.require("dojox.fx.style");_4.extend(_4.NodeList,{addClassFx:function(_7,_8){return _4.fx.combine(this.map(function(n){return _6.fx.addClass(n,_7,_8);}));},removeClassFx:function(_9,_a){return _4.fx.combine(this.map(function(n){return _6.fx.removeClass(n,_9,_a);}));},toggleClassFx:function(_b,_c,_d){return _4.fx.combine(this.map(function(n){return _6.fx.toggleClass(n,_b,_c,_d);}));}});}}};});