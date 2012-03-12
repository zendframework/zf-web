/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.Inline"],["require","dojox.dtl._base"],["require","dijit._Widget"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.Inline"]){_4._hasResource["dojox.dtl.Inline"]=true;_4.provide("dojox.dtl.Inline");_4.require("dojox.dtl._base");_4.require("dijit._Widget");_6.dtl.Inline=_4.extend(function(_7,_8){this.create(_7,_8);},_5._Widget.prototype,{context:null,render:function(_9){this.context=_9||this.context;this.postMixInProperties();_4.query("*",this.domNode).orphan();this.domNode.innerHTML=this.template.render(this.context);},declaredClass:"dojox.dtl.Inline",buildRendering:function(){var _a=this.domNode=document.createElement("div");var _b=this.srcNodeRef;if(_b.parentNode){_b.parentNode.replaceChild(_a,_b);}this.template=new _6.dtl.Template(_4.trim(_b.text),true);this.render();},postMixInProperties:function(){this.context=(this.context.get===_6.dtl._Context.prototype.get)?this.context:new _6.dtl._Context(this.context);}});}}};});