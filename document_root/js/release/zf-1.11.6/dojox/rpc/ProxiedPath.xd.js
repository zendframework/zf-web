/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.rpc.ProxiedPath"],["require","dojox.rpc.Service"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.rpc.ProxiedPath"]){_4._hasResource["dojox.rpc.ProxiedPath"]=true;_4.provide("dojox.rpc.ProxiedPath");_4.require("dojox.rpc.Service");_6.rpc.envelopeRegistry.register("PROXIED-PATH",function(_7){return _7=="PROXIED-PATH";},{serialize:function(_8,_9,_a){var i;var _b=_6.rpc.getTarget(_8,_9);if(_4.isArray(_a)){for(i=0;i<_a.length;i++){_b+="/"+(_a[i]==null?"":_a[i]);}}else{for(i in _a){_b+="/"+i+"/"+_a[i];}}return {data:"",target:(_9.proxyUrl||_8.proxyUrl)+"?url="+encodeURIComponent(_b)};},deserialize:function(_c){return _c;}});}}};});