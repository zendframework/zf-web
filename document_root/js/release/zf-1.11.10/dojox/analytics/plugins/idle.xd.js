/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.analytics._base"],["provide","dojox.analytics.plugins.idle"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.analytics.plugins.idle"]){_4._hasResource["dojox.analytics.plugins.idle"]=true;_4.require("dojox.analytics._base");_4.provide("dojox.analytics.plugins.idle");_6.analytics.plugins.idle=new (function(){this.addData=_4.hitch(_6.analytics,"addData","idle");this.idleTime=_4.config["idleTime"]||60000;this.idle=true;this.setIdle=function(){this.addData("isIdle");this.idle=true;};_4.addOnLoad(_4.hitch(this,function(){var _7=["onmousemove","onkeydown","onclick","onscroll"];for(var i=0;i<_7.length;i++){_4.connect(_4.doc,_7[i],this,function(e){if(this.idle){this.idle=false;this.addData("isActive");this.idleTimer=setTimeout(_4.hitch(this,"setIdle"),this.idleTime);}else{clearTimeout(this.idleTimer);this.idleTimer=setTimeout(_4.hitch(this,"setIdle"),this.idleTime);}});}}));})();}}};});