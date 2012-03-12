/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.Toolbar"],["require","dijit._Widget"],["require","dijit._KeyNavContainer"],["require","dijit._Templated"],["require","dijit.ToolbarSeparator"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.Toolbar"]){_4._hasResource["dijit.Toolbar"]=true;_4.provide("dijit.Toolbar");_4.require("dijit._Widget");_4.require("dijit._KeyNavContainer");_4.require("dijit._Templated");_4.declare("dijit.Toolbar",[_5._Widget,_5._Templated,_5._KeyNavContainer],{templateString:"<div class=\"dijit dijitToolbar\" waiRole=\"toolbar\" tabIndex=\"${tabIndex}\" dojoAttachPoint=\"containerNode\">"+"</div>",postCreate:function(){this.connectKeyNavHandlers(this.isLeftToRight()?[_4.keys.LEFT_ARROW]:[_4.keys.RIGHT_ARROW],this.isLeftToRight()?[_4.keys.RIGHT_ARROW]:[_4.keys.LEFT_ARROW]);},startup:function(){if(this._started){return;}this.startupKeyNavChildren();this.inherited(arguments);}});_4.require("dijit.ToolbarSeparator");}}};});