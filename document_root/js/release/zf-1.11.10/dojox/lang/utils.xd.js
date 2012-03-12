/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.utils"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.utils"]){_4._hasResource["dojox.lang.utils"]=true;_4.provide("dojox.lang.utils");(function(){var _7={},du=_6.lang.utils;var _8=function(o){if(_4.isArray(o)){return _4._toArray(o);}if(!_4.isObject(o)||_4.isFunction(o)){return o;}return _4.delegate(o);};_4.mixin(du,{coerceType:function(_9,_a){switch(typeof _9){case "number":return Number(eval("("+_a+")"));case "string":return String(_a);case "boolean":return Boolean(eval("("+_a+")"));}return eval("("+_a+")");},updateWithObject:function(_b,_c,_d){if(!_c){return _b;}for(var x in _b){if(x in _c&&!(x in _7)){var t=_b[x];if(t&&typeof t=="object"){du.updateWithObject(t,_c[x],_d);}else{_b[x]=_d?du.coerceType(t,_c[x]):_8(_c[x]);}}}return _b;},updateWithPattern:function(_e,_f,_10,_11){if(!_f||!_10){return _e;}for(var x in _10){if(x in _f&&!(x in _7)){_e[x]=_11?du.coerceType(_10[x],_f[x]):_8(_f[x]);}}return _e;}});})();}}};});