/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.functional.numrec"],["require","dojox.lang.functional.lambda"],["require","dojox.lang.functional.util"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.functional.numrec"]){_4._hasResource["dojox.lang.functional.numrec"]=true;_4.provide("dojox.lang.functional.numrec");_4.require("dojox.lang.functional.lambda");_4.require("dojox.lang.functional.util");(function(){var df=_6.lang.functional,_7=df.inlineLambda,_8=["_r","_i"];df.numrec=function(_9,_a){var a,as,_b={},_c=function(x){_b[x]=1;};if(typeof _a=="string"){as=_7(_a,_8,_c);}else{a=df.lambda(_a);as="_a.call(this, _r, _i)";}var _d=df.keys(_b),f=new Function(["_x"],"var _t=arguments.callee,_r=_t.t,_i".concat(_d.length?","+_d.join(","):"",a?",_a=_t.a":"",";for(_i=1;_i<=_x;++_i){_r=",as,"}return _r"));f.t=_9;if(a){f.a=a;}return f;};})();}}};});