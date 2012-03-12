/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.memoizerGuard"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.memoizerGuard"]){_4._hasResource["dojox.lang.aspect.memoizerGuard"]=true;_4.provide("dojox.lang.aspect.memoizerGuard");(function(){var _7=_6.lang.aspect,_8=function(_9){var _a=_7.getContext().instance,t;if(!(t=_a.__memoizerCache)){return;}if(arguments.length==0){delete _a.__memoizerCache;}else{if(_4.isArray(_9)){_4.forEach(_9,function(m){delete t[m];});}else{delete t[_9];}}};_7.memoizerGuard=function(_b){return {after:function(){_8(_b);}};};})();}}};});