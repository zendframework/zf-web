/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.tracer"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.tracer"]){_4._hasResource["dojox.lang.aspect.tracer"]=true;_4.provide("dojox.lang.aspect.tracer");(function(){var _7=_6.lang.aspect;var _8=function(_9){this.method=_9?"group":"log";if(_9){this.after=this._after;}};_4.extend(_8,{before:function(){var _a=_7.getContext(),_b=_a.joinPoint,_c=Array.prototype.join.call(arguments,", ");console[this.method](_a.instance,"=>",_b.targetName+"("+_c+")");},afterReturning:function(_d){var _e=_7.getContext().joinPoint;if(typeof _d!="undefined"){console.log(_e.targetName+"() returns:",_d);}else{console.log(_e.targetName+"() returns");}},afterThrowing:function(_f){console.log(_7.getContext().joinPoint.targetName+"() throws:",_f);},_after:function(_10){console.groupEnd();}});_7.tracer=function(_11){return new _8(_11);};})();}}};});