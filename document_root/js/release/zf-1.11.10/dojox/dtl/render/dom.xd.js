/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.render.dom"],["require","dojox.dtl.Context"],["require","dojox.dtl.dom"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.render.dom"]){_4._hasResource["dojox.dtl.render.dom"]=true;_4.provide("dojox.dtl.render.dom");_4.require("dojox.dtl.Context");_4.require("dojox.dtl.dom");_6.dtl.render.dom.Render=function(_7,_8){this._tpl=_8;this.domNode=_4.byId(_7);};_4.extend(_6.dtl.render.dom.Render,{setAttachPoint:function(_9){this.domNode=_9;},render:function(_a,_b,_c){if(!this.domNode){throw new Error("You cannot use the Render object without specifying where you want to render it");}this._tpl=_b=_b||this._tpl;_c=_c||_b.getBuffer();_a=_a||new _6.dtl.Context();var _d=_b.render(_a,_c).getParent();if(!_d){throw new Error("Rendered template does not have a root node");}if(this.domNode!==_d){this.domNode.parentNode.replaceChild(_d,this.domNode);this.domNode=_d;}}});}}};});