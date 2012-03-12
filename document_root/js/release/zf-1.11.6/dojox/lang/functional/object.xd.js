/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.functional.object"],["require","dojox.lang.functional.lambda"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.functional.object"]){_4._hasResource["dojox.lang.functional.object"]=true;_4.provide("dojox.lang.functional.object");_4.require("dojox.lang.functional.lambda");(function(){var d=_4,df=_6.lang.functional,_7={};d.mixin(df,{keys:function(_8){var t=[];for(var i in _8){if(!(i in _7)){t.push(i);}}return t;},values:function(_9){var t=[];for(var i in _9){if(!(i in _7)){t.push(_9[i]);}}return t;},filterIn:function(_a,f,o){o=o||d.global;f=df.lambda(f);var t={},v,i;for(i in _a){if(!(i in _7)){v=_a[i];if(f.call(o,v,i,_a)){t[i]=v;}}}return t;},forIn:function(_b,f,o){o=o||d.global;f=df.lambda(f);for(var i in _b){if(!(i in _7)){f.call(o,_b[i],i,_b);}}return o;},mapIn:function(_c,f,o){o=o||d.global;f=df.lambda(f);var t={},i;for(i in _c){if(!(i in _7)){t[i]=f.call(o,_c[i],i,_c);}}return t;}});})();}}};});