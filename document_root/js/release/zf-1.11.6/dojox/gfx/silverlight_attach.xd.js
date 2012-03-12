/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.gfx.silverlight"]],defineResource:function(_4,_5,_6){_4.require("dojox.gfx.silverlight");_4.experimental("dojox.gfx.silverlight_attach");(function(){_6.gfx.attachNode=function(_7){return null;if(!_7){return null;}var s=null;switch(_7.tagName.toLowerCase()){case _6.gfx.Rect.nodeType:s=new _6.gfx.Rect(_7);break;case _6.gfx.Ellipse.nodeType:if(_7.width==_7.height){s=new _6.gfx.Circle(_7);}else{s=new _6.gfx.Ellipse(_7);}break;case _6.gfx.Polyline.nodeType:s=new _6.gfx.Polyline(_7);break;case _6.gfx.Path.nodeType:s=new _6.gfx.Path(_7);break;case _6.gfx.Line.nodeType:s=new _6.gfx.Line(_7);break;case _6.gfx.Image.nodeType:s=new _6.gfx.Image(_7);break;case _6.gfx.Text.nodeType:s=new _6.gfx.Text(_7);_8(s);break;default:return null;}_9(s);if(!(s instanceof _6.gfx.Image)){_a(s);_b(s);}_c(s);return s;};_6.gfx.attachSurface=function(_d){return null;};var _a=function(_e){return null;};var _b=function(_f){return null;};var _c=function(_10){return null;};var _8=function(_11){return null;};var _9=function(_12){return null;};})();}};});