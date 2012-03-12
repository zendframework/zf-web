/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.rpc.JsonService"],["require","dojo.rpc.RpcService"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.rpc.JsonService"]){_4._hasResource["dojo.rpc.JsonService"]=true;_4.provide("dojo.rpc.JsonService");_4.require("dojo.rpc.RpcService");_4.declare("dojo.rpc.JsonService",_4.rpc.RpcService,{bustCache:false,contentType:"application/json-rpc",lastSubmissionId:0,callRemote:function(_7,_8){var _9=new _4.Deferred();this.bind(_7,_8,_9);return _9;},bind:function(_a,_b,_c,_d){var _e=_4.rawXhrPost({url:_d||this.serviceUrl,postData:this.createRequest(_a,_b),contentType:this.contentType,timeout:this.timeout,handleAs:"json-comment-optional"});_e.addCallbacks(this.resultCallback(_c),this.errorCallback(_c));},createRequest:function(_f,_10){var req={"params":_10,"method":_f,"id":++this.lastSubmissionId};var _11=_4.toJson(req);return _11;},parseResults:function(obj){if(_4.isObject(obj)){if("result" in obj){return obj.result;}if("Result" in obj){return obj.Result;}if("ResultSet" in obj){return obj.ResultSet;}}return obj;}});}}};});