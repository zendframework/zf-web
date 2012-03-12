/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.wire.TableAdapter"],["require","dojox.wire.CompositeWire"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.wire.TableAdapter"]){_4._hasResource["dojox.wire.TableAdapter"]=true;_4.provide("dojox.wire.TableAdapter");_4.require("dojox.wire.CompositeWire");_4.declare("dojox.wire.TableAdapter",_6.wire.CompositeWire,{_wireClass:"dojox.wire.TableAdapter",constructor:function(_7){this._initializeChildren(this.columns);},_getValue:function(_8){if(!_8||!this.columns){return _8;}var _9=_8;if(!_4.isArray(_9)){_9=[_9];}var _a=[];for(var i in _9){var _b=this._getRow(_9[i]);_a.push(_b);}return _a;},_setValue:function(_c,_d){throw new Error("Unsupported API: "+this._wireClass+"._setValue");},_getRow:function(_e){var _f=(_4.isArray(this.columns)?[]:{});for(var c in this.columns){_f[c]=this.columns[c].getValue(_e);}return _f;}});}}};});