/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.gfx.Mover"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.gfx.Mover"]){_4._hasResource["dojox.gfx.Mover"]=true;_4.provide("dojox.gfx.Mover");_4.declare("dojox.gfx.Mover",null,{constructor:function(_7,e,_8){this.shape=_7;this.lastX=e.clientX;this.lastY=e.clientY;var h=this.host=_8,d=document,_9=_4.connect(d,"onmousemove",this,"onFirstMove");this.events=[_4.connect(d,"onmousemove",this,"onMouseMove"),_4.connect(d,"onmouseup",this,"destroy"),_4.connect(d,"ondragstart",_4,"stopEvent"),_4.connect(d,"onselectstart",_4,"stopEvent"),_9];if(h&&h.onMoveStart){h.onMoveStart(this);}},onMouseMove:function(e){var x=e.clientX;var y=e.clientY;this.host.onMove(this,{dx:x-this.lastX,dy:y-this.lastY});this.lastX=x;this.lastY=y;_4.stopEvent(e);},onFirstMove:function(){this.host.onFirstMove(this);_4.disconnect(this.events.pop());},destroy:function(){_4.forEach(this.events,_4.disconnect);var h=this.host;if(h&&h.onMoveStop){h.onMoveStop(this);}this.events=this.shape=null;}});}}};});