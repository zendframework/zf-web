/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.wire.CompositeWire"],["require","dojox.wire._base"],["require","dojox.wire.Wire"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.wire.CompositeWire"]){_4._hasResource["dojox.wire.CompositeWire"]=true;_4.provide("dojox.wire.CompositeWire");_4.require("dojox.wire._base");_4.require("dojox.wire.Wire");_4.declare("dojox.wire.CompositeWire",_6.wire.Wire,{_wireClass:"dojox.wire.CompositeWire",constructor:function(_7){this._initializeChildren(this.children);},_getValue:function(_8){if(!_8||!this.children){return _8;}var _9=(_4.isArray(this.children)?[]:{});for(var c in this.children){_9[c]=this.children[c].getValue(_8);}return _9;},_setValue:function(_a,_b){if(!_a||!this.children){return _a;}for(var c in this.children){this.children[c].setValue(_b[c],_a);}return _a;},_initializeChildren:function(_c){if(!_c){return;}for(var c in _c){var _d=_c[c];_d.parent=this;if(!_6.wire.isWire(_d)){_c[c]=_6.wire.create(_d);}}}});}}};});