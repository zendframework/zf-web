/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.ext-dojo.NodeList"],["require","dojox.dtl._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.ext-dojo.NodeList"]){_4._hasResource["dojox.dtl.ext-dojo.NodeList"]=true;_4.provide("dojox.dtl.ext-dojo.NodeList");_4.require("dojox.dtl._base");_4.extend(_4.NodeList,{dtl:function(_7,_8){var d=_6.dtl;var _9=this;var _a=function(_b,_c){var _d=_b.render(new d._Context(_c));_9.forEach(function(_e){_e.innerHTML=_d;});};d.text._resolveTemplateArg(_7).addCallback(function(_f){_7=new d.Template(_f);d.text._resolveContextArg(_8).addCallback(function(_10){_a(_7,_10);});});return this;}});}}};});