/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.functional.listcomp"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.functional.listcomp"]){_4._hasResource["dojox.lang.functional.listcomp"]=true;_4.provide("dojox.lang.functional.listcomp");(function(){var _7=/\bfor\b|\bif\b/gm;var _8=function(s){var _9=s.split(_7),_a=s.match(_7),_b=["var r = [];"],_c=[],i=0,l=_a.length;while(i<l){var a=_a[i],f=_9[++i];if(a=="for"&&!/^\s*\(\s*(;|var)/.test(f)){f=f.replace(/^\s*\(/,"(var ");}_b.push(a,f,"{");_c.push("}");}return _b.join("")+"r.push("+_9[0]+");"+_c.join("")+"return r;";};_4.mixin(_6.lang.functional,{buildListcomp:function(s){return "function(){"+_8(s)+"}";},compileListcomp:function(s){return new Function([],_8(s));},listcomp:function(s){return (new Function([],_8(s)))();}});})();}}};});