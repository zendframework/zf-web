/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.manager._registry"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.manager._registry"]){_4._hasResource["dojox.drawing.manager._registry"]=true;_4.provide("dojox.drawing.manager._registry");(function(){var _7={tool:{},stencil:{},drawing:{},plugin:{}};_6.drawing.register=function(_8,_9){if(_9=="drawing"){_7.drawing[_8.id]=_8;}else{if(_9=="tool"){_7.tool[_8.name]=_8;}else{if(_9=="stencil"){_7.stencil[_8.name]=_8;}else{if(_9=="plugin"){_7.plugin[_8.name]=_8;}}}}};_6.drawing.getRegistered=function(_a,id){return id?_7[_a][id]:_7[_a];};})();}}};});