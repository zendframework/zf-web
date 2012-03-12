/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.widget.rotator.Fade"],["require","dojo.fx"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.widget.rotator.Fade"]){_4._hasResource["dojox.widget.rotator.Fade"]=true;_4.provide("dojox.widget.rotator.Fade");_4.require("dojo.fx");(function(d){function _7(_8,_9){var n=_8.next.node;d.style(n,{display:"",opacity:0});_8.node=_8.current.node;return d.fx[_9]([d.fadeOut(_8),d.fadeIn(d.mixin(_8,{node:n}))]);};d.mixin(_6.widget.rotator,{fade:function(_a){return _7(_a,"chain");},crossFade:function(_b){return _7(_b,"combine");}});})(_4);}}};});