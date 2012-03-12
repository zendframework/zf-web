/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.gfx3d._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.gfx3d._base"]){_4._hasResource["dojox.gfx3d._base"]=true;_4.provide("dojox.gfx3d._base");_4.mixin(_6.gfx3d,{defaultEdges:{type:"edges",style:null,points:[]},defaultTriangles:{type:"triangles",style:null,points:[]},defaultQuads:{type:"quads",style:null,points:[]},defaultOrbit:{type:"orbit",center:{x:0,y:0,z:0},radius:50},defaultPath3d:{type:"path3d",path:[]},defaultPolygon:{type:"polygon",path:[]},defaultCube:{type:"cube",bottom:{x:0,y:0,z:0},top:{x:100,y:100,z:100}},defaultCylinder:{type:"cylinder",center:{x:0,y:0,z:0},height:100,radius:50}});}}};});