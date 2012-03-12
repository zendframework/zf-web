/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.layout.dnd.Avatar"],["require","dojo.dnd.Avatar"],["require","dojo.dnd.common"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.layout.dnd.Avatar"]){_4._hasResource["dojox.layout.dnd.Avatar"]=true;_4.provide("dojox.layout.dnd.Avatar");_4.require("dojo.dnd.Avatar");_4.require("dojo.dnd.common");_4.declare("dojox.layout.dnd.Avatar",_4.dnd.Avatar,{constructor:function(_7,_8){this.opacity=_8||0.9;},construct:function(){var _9=this.manager.source,_a=_9.creator?_9._normalizedCreator(_9.getItem(this.manager.nodes[0].id).data,"avatar").node:this.manager.nodes[0].cloneNode(true);_4.addClass(_a,"dojoDndAvatar");_a.id=_4.dnd.getUniqueId();_a.style.position="absolute";_a.style.zIndex=1999;_a.style.margin="0px";_a.style.width=_4.marginBox(_9.node).w+"px";_4.style(_a,"opacity",this.opacity);this.node=_a;},update:function(){_4.toggleClass(this.node,"dojoDndAvatarCanDrop",this.manager.canDropFlag);},_generateText:function(){}});}}};});