/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.collections.Queue"],["require","dojox.collections._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.collections.Queue"]){_4._hasResource["dojox.collections.Queue"]=true;_4.provide("dojox.collections.Queue");_4.require("dojox.collections._base");_6.collections.Queue=function(_7){var q=[];if(_7){q=q.concat(_7);}this.count=q.length;this.clear=function(){q=[];this.count=q.length;};this.clone=function(){return new _6.collections.Queue(q);};this.contains=function(o){for(var i=0;i<q.length;i++){if(q[i]==o){return true;}}return false;};this.copyTo=function(_8,i){_8.splice(i,0,q);};this.dequeue=function(){var r=q.shift();this.count=q.length;return r;};this.enqueue=function(o){this.count=q.push(o);};this.forEach=function(fn,_9){_4.forEach(q,fn,_9);};this.getIterator=function(){return new _6.collections.Iterator(q);};this.peek=function(){return q[0];};this.toArray=function(){return [].concat(q);};};}}};});