/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.util.oo"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.util.oo"]){_4._hasResource["dojox.drawing.util.oo"]=true;_4.provide("dojox.drawing.util.oo");_6.drawing.util.oo={declare:function(){var f,o,_7=0,a=arguments;if(a.length<2){console.error("gfx.oo.declare; not enough arguments");}if(a.length==2){f=a[0];o=a[1];}else{a=Array.prototype.slice.call(arguments);o=a.pop();f=a.pop();_7=1;}for(var n in o){f.prototype[n]=o[n];}if(_7){a.unshift(f);f=this.extend.apply(this,a);}return f;},extend:function(){var a=arguments,_8=a[0];if(a.length<2){console.error("gfx.oo.extend; not enough arguments");}var f=function(){for(var i=1;i<a.length;i++){a[i].prototype.constructor.apply(this,arguments);}_8.prototype.constructor.apply(this,arguments);};for(var i=1;i<a.length;i++){for(var n in a[i].prototype){f.prototype[n]=a[i].prototype[n];}}for(var n in _8.prototype){f.prototype[n]=_8.prototype[n];}return f;}};}}};});