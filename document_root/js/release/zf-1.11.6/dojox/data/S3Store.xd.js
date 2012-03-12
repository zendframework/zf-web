/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.data.S3Store"],["require","dojox.rpc.ProxiedPath"],["require","dojox.data.JsonRestStore"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.data.S3Store"]){_4._hasResource["dojox.data.S3Store"]=true;_4.provide("dojox.data.S3Store");_4.require("dojox.rpc.ProxiedPath");_4.require("dojox.data.JsonRestStore");_4.declare("dojox.data.S3Store",_6.data.JsonRestStore,{_processResults:function(_7){var _8=_7.getElementsByTagName("Key");var _9=[];var _a=this;for(var i=0;i<_8.length;i++){var _b=_8[i];var _c={_loadObject:(function(_d,_e){return function(_f){delete this._loadObject;_a.service(_d).addCallback(_f);};})(_b.firstChild.nodeValue,_c)};_9.push(_c);}return {totalCount:_9.length,items:_9};}});}}};});