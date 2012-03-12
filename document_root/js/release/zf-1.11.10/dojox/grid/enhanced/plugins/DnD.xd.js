/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.grid.enhanced.plugins.DnD"],["require","dojox.grid.enhanced.dnd._DndMovingManager"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.grid.enhanced.plugins.DnD"]){_4._hasResource["dojox.grid.enhanced.plugins.DnD"]=true;_4.provide("dojox.grid.enhanced.plugins.DnD");_4.require("dojox.grid.enhanced.dnd._DndMovingManager");_4.declare("dojox.grid.enhanced.plugins.DnD",_6.grid.enhanced.dnd._DndMovingManager,{});}}};});