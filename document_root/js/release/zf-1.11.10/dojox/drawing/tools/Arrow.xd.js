/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.tools.Arrow"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.tools.Arrow"]){_4._hasResource["dojox.drawing.tools.Arrow"]=true;_4.provide("dojox.drawing.tools.Arrow");_6.drawing.tools.Arrow=_6.drawing.util.oo.declare(_6.drawing.tools.Line,function(_7){if(this.arrowStart){this.begArrow=new _6.drawing.annotations.Arrow({stencil:this,idx1:0,idx2:1});}if(this.arrowEnd){this.endArrow=new _6.drawing.annotations.Arrow({stencil:this,idx1:1,idx2:0});}if(this.points.length){this.render();}},{draws:true,type:"dojox.drawing.tools.Arrow",baseRender:false,arrowStart:false,arrowEnd:true,onUp:function(_8){if(this.created||!this.shape){return;}var p=this.points;var _9=this.util.distance(p[0].x,p[0].y,p[1].x,p[1].y);if(_9<this.minimumSize){this.remove(this.shape,this.hit);return;}var pt=this.util.snapAngle(_8,this.angleSnap/180);this.setPoints([{x:p[0].x,y:p[0].y},{x:pt.x,y:pt.y}]);this.renderedOnce=true;this.onRender(this);}});_6.drawing.tools.Arrow.setup={name:"dojox.drawing.tools.Arrow",tooltip:"Arrow Tool",iconClass:"iconArrow"};_6.drawing.register(_6.drawing.tools.Arrow.setup,"tool");}}};});