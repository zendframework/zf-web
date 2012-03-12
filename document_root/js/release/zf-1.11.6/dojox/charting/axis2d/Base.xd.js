/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.axis2d.Base"],["require","dojox.charting.Element"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.axis2d.Base"]){_4._hasResource["dojox.charting.axis2d.Base"]=true;_4.provide("dojox.charting.axis2d.Base");_4.require("dojox.charting.Element");_4.declare("dojox.charting.axis2d.Base",_6.charting.Element,{constructor:function(_7,_8){this.vertical=_8&&_8.vertical;},clear:function(){return this;},initialized:function(){return false;},calculate:function(_9,_a,_b){return this;},getScaler:function(){return null;},getTicks:function(){return null;},getOffsets:function(){return {l:0,r:0,t:0,b:0};},render:function(_c,_d){return this;}});}}};});