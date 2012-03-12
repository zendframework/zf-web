/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.async.timeout"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.async.timeout"]){_4._hasResource["dojox.lang.async.timeout"]=true;_4.provide("dojox.lang.async.timeout");(function(){var d=_4,_7=_6.lang.async.timeout;_7.from=function(ms){return function(){var h,_8=function(){if(h){clearTimeout(h);h=null;}},x=new d.Deferred(_8);h=setTimeout(function(){_8();x.callback(ms);},ms);return x;};};_7.failOn=function(ms){return function(){var h,_9=function(){if(h){clearTimeout(h);h=null;}},x=new d.Deferred(_9);h=setTimeout(function(){_9();x.errback(ms);},ms);return x;};};})();}}};});