/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit._editor.plugins.TextColor"],["require","dijit._editor._Plugin"],["require","dijit.ColorPalette"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit._editor.plugins.TextColor"]){_4._hasResource["dijit._editor.plugins.TextColor"]=true;_4.provide("dijit._editor.plugins.TextColor");_4.require("dijit._editor._Plugin");_4.require("dijit.ColorPalette");_4.declare("dijit._editor.plugins.TextColor",_5._editor._Plugin,{buttonClass:_5.form.DropDownButton,constructor:function(){this.dropDown=new _5.ColorPalette();this.connect(this.dropDown,"onChange",function(_7){this.editor.execCommand(this.command,_7);});}});_4.subscribe(_5._scopeName+".Editor.getPlugin",null,function(o){if(o.plugin){return;}switch(o.args.name){case "foreColor":case "hiliteColor":o.plugin=new _5._editor.plugins.TextColor({command:o.args.name});}});}}};});