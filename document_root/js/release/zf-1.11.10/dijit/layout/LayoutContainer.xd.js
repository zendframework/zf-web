/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.layout.LayoutContainer"],["require","dijit.layout._LayoutWidget"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.layout.LayoutContainer"]){_4._hasResource["dijit.layout.LayoutContainer"]=true;_4.provide("dijit.layout.LayoutContainer");_4.require("dijit.layout._LayoutWidget");_4.declare("dijit.layout.LayoutContainer",_5.layout._LayoutWidget,{baseClass:"dijitLayoutContainer",constructor:function(){_4.deprecated("dijit.layout.LayoutContainer is deprecated","use BorderContainer instead",2);},layout:function(){_5.layout.layoutChildren(this.domNode,this._contentBox,this.getChildren());},addChild:function(_7,_8){this.inherited(arguments);if(this._started){_5.layout.layoutChildren(this.domNode,this._contentBox,this.getChildren());}},removeChild:function(_9){this.inherited(arguments);if(this._started){_5.layout.layoutChildren(this.domNode,this._contentBox,this.getChildren());}}});_4.extend(_5._Widget,{layoutAlign:"none"});}}};});