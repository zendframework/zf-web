/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.async.event"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.async.event"]){_4._hasResource["dojox.lang.async.event"]=true;_4.provide("dojox.lang.async.event");(function(){var d=_4,_7=_6.lang.async.event;_7.from=function(_8,_9){return function(){var h,_a=function(){if(h){d.disconnect(h);h=null;}},x=new d.Deferred(_a);h=d.connect(_8,_9,function(_b){_a();x.callback(_b);});return x;};};_7.failOn=function(_c,_d){return function(){var h,_e=function(){if(h){d.disconnect(h);h=null;}},x=new d.Deferred(_e);h=d.connect(_c,_d,function(_f){_e();x.errback(new Error(_f));});return x;};};})();}}};});