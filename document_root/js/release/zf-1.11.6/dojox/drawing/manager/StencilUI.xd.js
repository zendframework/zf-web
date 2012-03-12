/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.manager.StencilUI"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.manager.StencilUI"]){_4._hasResource["dojox.drawing.manager.StencilUI"]=true;_4.provide("dojox.drawing.manager.StencilUI");(function(){var _7,_8;_6.drawing.manager.StencilUI=_6.drawing.util.oo.declare(function(_9){_7=_9.surface;this.canvas=_9.canvas;this.defaults=_6.drawing.defaults.copy();this.mouse=_9.mouse;this.keys=_9.keys;this._mouseHandle=this.mouse.register(this);this.stencils={};},{register:function(_a){this.stencils[_a.id]=_a;return _a;},onUiDown:function(_b){if(!this._isStencil(_b)){return;}this.stencils[_b.id].onDown(_b);},onUiUp:function(_c){if(!this._isStencil(_c)){return;}this.stencils[_c.id].onUp(_c);},onOver:function(_d){if(!this._isStencil(_d)){return;}this.stencils[_d.id].onOver(_d);},onOut:function(_e){if(!this._isStencil(_e)){return;}this.stencils[_e.id].onOut(_e);},_isStencil:function(_f){return !!_f.id&&!!this.stencils[_f.id]&&this.stencils[_f.id].type=="drawing.library.UI.Button";}});})();}}};});