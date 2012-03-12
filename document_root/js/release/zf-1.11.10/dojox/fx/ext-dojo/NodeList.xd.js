/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.fx.ext-dojo.NodeList"],["require","dojo.NodeList-fx"],["require","dojox.fx"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.fx.ext-dojo.NodeList"]){_4._hasResource["dojox.fx.ext-dojo.NodeList"]=true;_4.provide("dojox.fx.ext-dojo.NodeList");_4.experimental("dojox.fx.ext-dojo.NodeList");_4.require("dojo.NodeList-fx");_4.require("dojox.fx");_4.extend(_4.NodeList,{sizeTo:function(_7){return this._anim(_6.fx,"sizeTo",_7);},slideBy:function(_8){return this._anim(_6.fx,"slideBy",_8);},highlight:function(_9){return this._anim(_6.fx,"highlight",_9);},fadeTo:function(_a){return this._anim(_4,"_fade",_a);},wipeTo:function(_b){return this._anim(_6.fx,"wipeTo",_b);}});}}};});