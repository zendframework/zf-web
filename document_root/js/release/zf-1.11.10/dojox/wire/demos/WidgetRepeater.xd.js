/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.wire.demos.WidgetRepeater"],["require","dojo.parser"],["require","dijit._Widget"],["require","dijit._Templated"],["require","dijit._Container"],["require",this.widget]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.wire.demos.WidgetRepeater"]){_4._hasResource["dojox.wire.demos.WidgetRepeater"]=true;_4.provide("dojox.wire.demos.WidgetRepeater");_4.require("dojo.parser");_4.require("dijit._Widget");_4.require("dijit._Templated");_4.require("dijit._Container");_4.declare("dojox.wire.demos.WidgetRepeater",[_5._Widget,_5._Templated,_5._Container],{templateString:"<div class='WidgetRepeater' dojoAttachPoint='repeaterNode'></div>",widget:null,repeater:null,createNew:function(_7){try{if(_4.isString(this.widget)){_4.require(this.widget);this.widget=_4.getObject(this.widget);}this.addChild(new this.widget(_7));this.repeaterNode.appendChild(document.createElement("br"));}catch(e){console.debug(e);}}});}}};});