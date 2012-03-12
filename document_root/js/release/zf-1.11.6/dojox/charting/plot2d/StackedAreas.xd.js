/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.plot2d.StackedAreas"],["require","dojox.charting.plot2d.Stacked"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.plot2d.StackedAreas"]){_4._hasResource["dojox.charting.plot2d.StackedAreas"]=true;_4.provide("dojox.charting.plot2d.StackedAreas");_4.require("dojox.charting.plot2d.Stacked");_4.declare("dojox.charting.plot2d.StackedAreas",_6.charting.plot2d.Stacked,{constructor:function(){this.opt.lines=true;this.opt.areas=true;}});}}};});