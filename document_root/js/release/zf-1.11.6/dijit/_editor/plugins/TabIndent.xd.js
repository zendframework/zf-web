/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit._editor.plugins.TabIndent"],["require","dijit._editor._Plugin"],["require","dijit.form.ToggleButton"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit._editor.plugins.TabIndent"]){_4._hasResource["dijit._editor.plugins.TabIndent"]=true;_4.provide("dijit._editor.plugins.TabIndent");_4.experimental("dijit._editor.plugins.TabIndent");_4.require("dijit._editor._Plugin");_4.require("dijit.form.ToggleButton");_4.declare("dijit._editor.plugins.TabIndent",_5._editor._Plugin,{useDefaultCommand:false,buttonClass:_5.form.ToggleButton,command:"tabIndent",_initButton:function(){this.inherited(arguments);var e=this.editor;this.connect(this.button,"onChange",function(_7){e.attr("isTabIndent",_7);});this.updateState();},updateState:function(){this.button.attr("checked",this.editor.isTabIndent,false);}});_4.subscribe(_5._scopeName+".Editor.getPlugin",null,function(o){if(o.plugin){return;}switch(o.args.name){case "tabIndent":o.plugin=new _5._editor.plugins.TabIndent({command:o.args.name});}});}}};});