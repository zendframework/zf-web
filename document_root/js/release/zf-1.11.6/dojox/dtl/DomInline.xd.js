/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.DomInline"],["require","dojox.dtl.dom"],["require","dijit._Widget"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.DomInline"]){_4._hasResource["dojox.dtl.DomInline"]=true;_4.provide("dojox.dtl.DomInline");_4.require("dojox.dtl.dom");_4.require("dijit._Widget");_6.dtl.DomInline=_4.extend(function(_7,_8){this.create(_7,_8);},_5._Widget.prototype,{context:null,render:function(_9){this.context=_9||this.context;this.postMixInProperties();var _a=this.template.render(this.context).getRootNode();if(_a!=this.containerNode){this.containerNode.parentNode.replaceChild(_a,this.containerNode);this.containerNode=_a;}},declaredClass:"dojox.dtl.Inline",buildRendering:function(){var _b=this.domNode=document.createElement("div");this.containerNode=_b.appendChild(document.createElement("div"));var _c=this.srcNodeRef;if(_c.parentNode){_c.parentNode.replaceChild(_b,_c);}this.template=new _6.dtl.DomTemplate(_4.trim(_c.text),true);this.render();},postMixInProperties:function(){this.context=(this.context.get===_6.dtl._Context.prototype.get)?this.context:new _6.dtl.Context(this.context);}});}}};});