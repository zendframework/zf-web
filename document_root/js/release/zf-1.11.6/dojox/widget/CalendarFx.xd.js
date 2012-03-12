/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.widget.CalendarFx"],["require","dojox.widget.FisheyeLite"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.widget.CalendarFx"]){_4._hasResource["dojox.widget.CalendarFx"]=true;_4.provide("dojox.widget.CalendarFx");_4.require("dojox.widget.FisheyeLite");_4.declare("dojox.widget._FisheyeFX",null,{addFx:function(_7,_8){_4.query(_7,_8).forEach(function(_9){new _6.widget.FisheyeLite({properties:{fontSize:1.1}},_9);});}});_4.declare("dojox.widget.CalendarFisheye",[_6.widget.Calendar,_6.widget._FisheyeFX],{});}}};});