/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.embed.flashVars"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.embed.flashVars"]){_4._hasResource["dojox.embed.flashVars"]=true;_4.provide("dojox.embed.flashVars");_4.mixin(_6.embed.flashVars,{serialize:function(n,o){var _7=function(_8){if(typeof _8=="string"){_8=_8.replace(/;/g,"_sc_");_8=_8.replace(/\./g,"_pr_");_8=_8.replace(/\:/g,"_cl_");}return _8;};var df=_6.embed.flashVars.serialize;var _9="";if(_4.isArray(o)){for(var i=0;i<o.length;i++){_9+=df(n+"."+i,_7(o[i]))+";";}return _9.replace(/;{2,}/g,";");}else{if(_4.isObject(o)){for(var nm in o){_9+=df(n+"."+nm,_7(o[nm]))+";";}return _9.replace(/;{2,}/g,";");}}return n+":"+o;}});}}};});