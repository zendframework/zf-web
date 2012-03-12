/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.timing._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.timing._base"]){_4._hasResource["dojox.timing._base"]=true;_4.provide("dojox.timing._base");_4.experimental("dojox.timing");_6.timing.Timer=function(_7){this.timer=null;this.isRunning=false;this.interval=_7;this.onStart=null;this.onStop=null;};_4.extend(_6.timing.Timer,{onTick:function(){},setInterval:function(_8){if(this.isRunning){window.clearInterval(this.timer);}this.interval=_8;if(this.isRunning){this.timer=window.setInterval(_4.hitch(this,"onTick"),this.interval);}},start:function(){if(typeof this.onStart=="function"){this.onStart();}this.isRunning=true;this.timer=window.setInterval(_4.hitch(this,"onTick"),this.interval);},stop:function(){if(typeof this.onStop=="function"){this.onStop();}this.isRunning=false;window.clearInterval(this.timer);}});}}};});