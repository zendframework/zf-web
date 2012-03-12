/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.grid.enhanced.dnd._DndRowSelector"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.grid.enhanced.dnd._DndRowSelector"]){_4._hasResource["dojox.grid.enhanced.dnd._DndRowSelector"]=true;_4.provide("dojox.grid.enhanced.dnd._DndRowSelector");_4.declare("dojox.grid.enhanced.dnd._DndRowSelector",null,{domousedown:function(e){this.grid.onMouseDown(e);},domouseup:function(e){this.grid.onMouseUp(e);},dofocus:function(e){e.cellNode.style.border="solid 1px";}});}}};});