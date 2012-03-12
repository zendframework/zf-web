/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.gfx"],["requireIf",_3.gfx.renderer=="svg","dojox.gfx.svg_attach"],["requireIf",_3.gfx.renderer=="vml","dojox.gfx.vml_attach"],["requireIf",_3.gfx.renderer=="silverlight","dojox.gfx.silverlight_attach"],["requireIf",_3.gfx.renderer=="canvas","dojox.gfx.canvas_attach"]],defineResource:function(_4,_5,_6){_4.require("dojox.gfx");_4.requireIf(_6.gfx.renderer=="svg","dojox.gfx.svg_attach");_4.requireIf(_6.gfx.renderer=="vml","dojox.gfx.vml_attach");_4.requireIf(_6.gfx.renderer=="silverlight","dojox.gfx.silverlight_attach");_4.requireIf(_6.gfx.renderer=="canvas","dojox.gfx.canvas_attach");}};});