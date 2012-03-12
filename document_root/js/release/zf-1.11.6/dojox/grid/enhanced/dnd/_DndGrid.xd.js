/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.grid.enhanced.dnd._DndGrid"],["require","dojox.grid.enhanced.dnd._DndEvents"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.grid.enhanced.dnd._DndGrid"]){_4._hasResource["dojox.grid.enhanced.dnd._DndGrid"]=true;_4.provide("dojox.grid.enhanced.dnd._DndGrid");_4.require("dojox.grid.enhanced.dnd._DndEvents");_4.declare("dojox.grid.enhanced.dnd._DndGrid",_6.grid.enhanced.dnd._DndEvents,{select:null,dndSelectable:true,constructor:function(_7){this.select=_7;},domousedown:function(e){if(!e.cellNode){this.onRowHeaderMouseDown(e);}},domouseup:function(e){if(!e.cellNode){this.onRowHeaderMouseUp(e);}}});}}};});