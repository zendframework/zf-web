/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.grid.enhanced.dnd._DndMover"],["require","dojo.dnd.move"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.grid.enhanced.dnd._DndMover"]){_4._hasResource["dojox.grid.enhanced.dnd._DndMover"]=true;_4.provide("dojox.grid.enhanced.dnd._DndMover");_4.require("dojo.dnd.move");_4.declare("dojox.grid.enhanced.dnd._DndMover",_4.dnd.Mover,{onMouseMove:function(e){_4.dnd.autoScroll(e);var m=this.marginBox;this.host.onMove(this,{l:m.l+e.pageX,t:m.t+e.pageY},{x:e.pageX,y:e.pageY});_4.stopEvent(e);}});_4.declare("dojox.grid.enhanced.dnd._DndBoxConstrainedMoveable",_4.dnd.move.boxConstrainedMoveable,{movingType:"row",constructor:function(_7,_8){if(!_8||!_8.movingType){return;}this.movingType=_8.movingType;},onFirstMove:function(_9){this.inherited(arguments);if(this.within){var c=this.constraintBox,mb=_4.marginBox(_9.node);if(this.movingType=="row"){c.r+=mb.w;}else{if(this.movingType=="col"){c.b+=mb.h;}}}}});}}};});