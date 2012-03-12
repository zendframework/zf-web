/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.editor.plugins.ToolbarLineBreak"],["require","dijit._Widget"],["require","dijit._Templated"],["require","dijit._editor._Plugin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.editor.plugins.ToolbarLineBreak"]){_4._hasResource["dojox.editor.plugins.ToolbarLineBreak"]=true;_4.provide("dojox.editor.plugins.ToolbarLineBreak");_4.require("dijit._Widget");_4.require("dijit._Templated");_4.require("dijit._editor._Plugin");_4.declare("dojox.editor.plugins._ToolbarLineBreak",[_5._Widget,_5._Templated],{templateString:"<span class='dijit dijitReset'><br></span>",postCreate:function(){_4.setSelectable(this.domNode,false);},isFocusable:function(){return false;}});_4.subscribe(_5._scopeName+".Editor.getPlugin",null,function(o){if(o.plugin){return;}var _7=o.args.name.toLowerCase();if(_7==="||"||_7==="toolbarlinebreak"){o.plugin=new _5._editor._Plugin({button:new _6.editor.plugins._ToolbarLineBreak()});}});}}};});