/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.counter"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.counter"]){_4._hasResource["dojox.lang.aspect.counter"]=true;_4.provide("dojox.lang.aspect.counter");(function(){var _7=_6.lang.aspect;var _8=function(){this.reset();};_4.extend(_8,{before:function(){++this.calls;},afterThrowing:function(){++this.errors;},reset:function(){this.calls=this.errors=0;}});_7.counter=function(){return new _8;};})();}}};});