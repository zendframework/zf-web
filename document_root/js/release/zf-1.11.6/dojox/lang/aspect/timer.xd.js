/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.timer"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.timer"]){_4._hasResource["dojox.lang.aspect.timer"]=true;_4.provide("dojox.lang.aspect.timer");(function(){var _7=_6.lang.aspect,_8=0;var _9=function(_a){this.name=_a||("DojoAopTimer #"+ ++_8);this.inCall=0;};_4.extend(_9,{before:function(){if(!(this.inCall++)){console.time(this.name);}},after:function(){if(!--this.inCall){console.timeEnd(this.name);}}});_7.timer=function(_b){return new _9(_b);};})();}}};});