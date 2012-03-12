/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.data.demos.widgets.PicasaViewList"],["require","dijit._Templated"],["require","dijit._Widget"],["require","dojox.data.demos.widgets.PicasaView"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.data.demos.widgets.PicasaViewList"]){_4._hasResource["dojox.data.demos.widgets.PicasaViewList"]=true;_4.provide("dojox.data.demos.widgets.PicasaViewList");_4.require("dijit._Templated");_4.require("dijit._Widget");_4.require("dojox.data.demos.widgets.PicasaView");_4.declare("dojox.data.demos.widgets.PicasaViewList",[_5._Widget,_5._Templated],{templateString:_4.cache("dojox","data/demos/widgets/templates/PicasaViewList.html","<div dojoAttachPoint=\"list\"></div>\n\n"),listNode:null,postCreate:function(){this.fViewWidgets=[];},clearList:function(){while(this.list.firstChild){this.list.removeChild(this.list.firstChild);}for(var i=0;i<this.fViewWidgets.length;i++){this.fViewWidgets[i].destroy();}this.fViewWidgets=[];},addView:function(_7){var _8=new _6.data.demos.widgets.PicasaView(_7);this.fViewWidgets.push(_8);this.list.appendChild(_8.domNode);}});}}};});