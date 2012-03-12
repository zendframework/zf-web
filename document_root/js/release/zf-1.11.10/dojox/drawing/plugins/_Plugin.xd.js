/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.plugins._Plugin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.plugins._Plugin"]){_4._hasResource["dojox.drawing.plugins._Plugin"]=true;_4.provide("dojox.drawing.plugins._Plugin");_6.drawing.plugins._Plugin=_6.drawing.util.oo.declare(function(_7){this._cons=[];_4.mixin(this,_7);if(this.button&&this.onClick){this.connect(this.button,"onClick",this,"onClick");}},{util:null,keys:null,mouse:null,drawing:null,stencils:null,anchors:null,canvas:null,node:null,button:null,type:"dojox.drawing.plugins._Plugin",connect:function(){this._cons.push(_4.connect.apply(_4,arguments));},disconnect:function(_8){if(!_8){return;}if(!_4.isArray(_8)){_8=[_8];}_4.forEach(_8,_4.disconnect,_4);}});}}};});