/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.collections._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.collections._base"]){_4._hasResource["dojox.collections._base"]=true;_4.provide("dojox.collections._base");_6.collections.DictionaryEntry=function(k,v){this.key=k;this.value=v;this.valueOf=function(){return this.value;};this.toString=function(){return String(this.value);};};_6.collections.Iterator=function(_7){var a=_7;var _8=0;this.element=a[_8]||null;this.atEnd=function(){return (_8>=a.length);};this.get=function(){if(this.atEnd()){return null;}this.element=a[_8++];return this.element;};this.map=function(fn,_9){return _4.map(a,fn,_9);};this.reset=function(){_8=0;this.element=a[_8];};};_6.collections.DictionaryIterator=function(_a){var a=[];var _b={};for(var p in _a){if(!_b[p]){a.push(_a[p]);}}var _c=0;this.element=a[_c]||null;this.atEnd=function(){return (_c>=a.length);};this.get=function(){if(this.atEnd()){return null;}this.element=a[_c++];return this.element;};this.map=function(fn,_d){return _4.map(a,fn,_d);};this.reset=function(){_c=0;this.element=a[_c];};};}}};});