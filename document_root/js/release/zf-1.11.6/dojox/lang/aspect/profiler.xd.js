/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.profiler"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.profiler"]){_4._hasResource["dojox.lang.aspect.profiler"]=true;_4.provide("dojox.lang.aspect.profiler");(function(){var _7=_6.lang.aspect,_8=0;var _9=function(_a){this.args=_a?[_a]:[];this.inCall=0;};_4.extend(_9,{before:function(){if(!(this.inCall++)){console.profile.apply(console,this.args);}},after:function(){if(!--this.inCall){console.profileEnd();}}});_7.profiler=function(_b){return new _9(_b);};})();}}};});