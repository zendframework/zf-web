/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.data.restListener"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.data.restListener"]){_4._hasResource["dojox.data.restListener"]=true;_4.provide("dojox.data.restListener");_6.data.restListener=function(_7){var _8=_7.channel;var jr=_6.rpc.JsonRest;var _9=jr.getServiceAndId(_8).service;var _a=_6.json.ref.resolveJson(_7.result,{defaultId:_7.event=="put"&&_8,index:_6.rpc.Rest._index,idPrefix:_9.servicePath.replace(/[^\/]*$/,""),idAttribute:jr.getIdAttribute(_9),schemas:jr.schemas,loader:jr._loader,assignAbsoluteIds:true});var _b=_6.rpc.Rest._index&&_6.rpc.Rest._index[_8];var _c="on"+_7.event.toLowerCase();var _d=_9&&_9._store;if(_b){if(_b[_c]){_b[_c](_a);return;}}if(_d){switch(_c){case "onpost":_d.onNew(_a);break;case "ondelete":_d.onDelete(_b);break;}}};}}};});