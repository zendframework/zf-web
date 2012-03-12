/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.rpc.JsonpService"],["require","dojo.rpc.RpcService"],["require","dojo.io.script"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.rpc.JsonpService"]){_4._hasResource["dojo.rpc.JsonpService"]=true;_4.provide("dojo.rpc.JsonpService");_4.require("dojo.rpc.RpcService");_4.require("dojo.io.script");_4.declare("dojo.rpc.JsonpService",_4.rpc.RpcService,{constructor:function(_7,_8){if(this.required){if(_8){_4.mixin(this.required,_8);}_4.forEach(this.required,function(_9){if(_9==""||_9==undefined){throw new Error("Required Service Argument not found: "+_9);}});}},strictArgChecks:false,bind:function(_a,_b,_c,_d){var _e=_4.io.script.get({url:_d||this.serviceUrl,callbackParamName:this.callbackParamName||"callback",content:this.createRequest(_b),timeout:this.timeout,handleAs:"json",preventCache:true});_e.addCallbacks(this.resultCallback(_c),this.errorCallback(_c));},createRequest:function(_f){var _10=(_4.isArrayLike(_f)&&_f.length==1)?_f[0]:{};_4.mixin(_10,this.required);return _10;}});}}};});