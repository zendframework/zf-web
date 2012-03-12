/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.layout.DragPane"],["require","dijit._Widget"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.layout.DragPane"]){_4._hasResource["dojox.layout.DragPane"]=true;_4.provide("dojox.layout.DragPane");_4.require("dijit._Widget");_4.declare("dojox.layout.DragPane",_5._Widget,{invert:true,postCreate:function(){this.inherited(arguments);this.connect(this.domNode,"onmousedown","_down");this.connect(this.domNode,"onmouseup","_up");},_down:function(e){var t=this.domNode;_4.style(t,"cursor","move");this._x=e.pageX;this._y=e.pageY;if((this._x<t.offsetLeft+t.clientWidth)&&(this._y<t.offsetTop+t.clientHeight)){_4.setSelectable(t,false);this._mover=this.connect(t,"onmousemove","_move");}},_up:function(e){_4.setSelectable(this.domNode,true);_4.style(this.domNode,"cursor","pointer");this.disconnect(this._mover);},_move:function(e){var _7=this.invert?1:-1;this.domNode.scrollTop+=(this._y-e.pageY)*_7;this.domNode.scrollLeft+=(this._x-e.pageX)*_7;this._x=e.pageX;this._y=e.pageY;}});}}};});