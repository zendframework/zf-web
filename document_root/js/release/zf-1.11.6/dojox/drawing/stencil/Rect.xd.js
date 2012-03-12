/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.stencil.Rect"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.stencil.Rect"]){_4._hasResource["dojox.drawing.stencil.Rect"]=true;_4.provide("dojox.drawing.stencil.Rect");_6.drawing.stencil.Rect=_6.drawing.util.oo.declare(_6.drawing.stencil._Base,function(_7){if(this.points.length){}},{type:"dojox.drawing.stencil.Rect",anchorType:"group",baseRender:true,dataToPoints:function(d){d=d||this.data;this.points=[{x:d.x,y:d.y},{x:d.x+d.width,y:d.y},{x:d.x+d.width,y:d.y+d.height},{x:d.x,y:d.y+d.height}];return this.points;},pointsToData:function(p){p=p||this.points;var s=p[0];var e=p[2];this.data={x:s.x,y:s.y,width:e.x-s.x,height:e.y-s.y,r:this.data.r||0};return this.data;},_create:function(_8,d,_9){this.remove(this[_8]);this[_8]=this.container.createRect(d).setStroke(_9).setFill(_9.fill);this._setNodeAtts(this[_8]);},render:function(){this.onBeforeRender(this);this.renderHit&&this._create("hit",this.data,this.style.currentHit);this._create("shape",this.data,this.style.current);}});_6.drawing.register({name:"dojox.drawing.stencil.Rect"},"stencil");}}};});