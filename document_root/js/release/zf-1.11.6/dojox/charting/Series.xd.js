/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.Series"],["require","dojox.charting.Element"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.Series"]){_4._hasResource["dojox.charting.Series"]=true;_4.provide("dojox.charting.Series");_4.require("dojox.charting.Element");_4.declare("dojox.charting.Series",_6.charting.Element,{constructor:function(_7,_8,_9){_4.mixin(this,_9);if(typeof this.plot!="string"){this.plot="default";}this.data=_8;this.dirty=true;this.clear();},clear:function(){this.dyn={};}});}}};});