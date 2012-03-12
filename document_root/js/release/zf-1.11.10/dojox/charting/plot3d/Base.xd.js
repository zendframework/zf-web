/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.plot3d.Base"],["require","dojox.charting.Chart3D"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.plot3d.Base"]){_4._hasResource["dojox.charting.plot3d.Base"]=true;_4.provide("dojox.charting.plot3d.Base");_4.require("dojox.charting.Chart3D");_4.declare("dojox.charting.plot3d.Base",null,{constructor:function(_7,_8,_9){this.width=_7;this.height=_8;},setData:function(_a){this.data=_a?_a:[];return this;},getDepth:function(){return this.depth;},generate:function(_b,_c){}});}}};});