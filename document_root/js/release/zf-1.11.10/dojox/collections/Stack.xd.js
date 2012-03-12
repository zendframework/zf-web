/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.collections.Stack"],["require","dojox.collections._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.collections.Stack"]){_4._hasResource["dojox.collections.Stack"]=true;_4.provide("dojox.collections.Stack");_4.require("dojox.collections._base");_6.collections.Stack=function(_7){var q=[];if(_7){q=q.concat(_7);}this.count=q.length;this.clear=function(){q=[];this.count=q.length;};this.clone=function(){return new _6.collections.Stack(q);};this.contains=function(o){for(var i=0;i<q.length;i++){if(q[i]==o){return true;}}return false;};this.copyTo=function(_8,i){_8.splice(i,0,q);};this.forEach=function(fn,_9){_4.forEach(q,fn,_9);};this.getIterator=function(){return new _6.collections.Iterator(q);};this.peek=function(){return q[(q.length-1)];};this.pop=function(){var r=q.pop();this.count=q.length;return r;};this.push=function(o){this.count=q.push(o);};this.toArray=function(){return [].concat(q);};};}}};});