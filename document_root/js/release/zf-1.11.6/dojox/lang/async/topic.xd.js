/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.async.topic"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.async.topic"]){_4._hasResource["dojox.lang.async.topic"]=true;_4.provide("dojox.lang.async.topic");(function(){var d=_4,_7=_6.lang.async.topic;_7.from=function(_8){return function(){var h,_9=function(){if(h){d.unsubscribe(h);h=null;}},x=new d.Deferred(_9);h=d.subscribe(_8,function(){_9();x.callback(arguments);});return x;};};_7.failOn=function(_a){return function(){var h,_b=function(){if(h){d.unsubscribe(h);h=null;}},x=new d.Deferred(_b);h=d.subscribe(_a,function(_c){_b();x.errback(new Error(arguments));});return x;};};})();}}};});