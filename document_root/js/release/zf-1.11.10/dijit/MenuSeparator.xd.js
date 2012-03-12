/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.MenuSeparator"],["require","dijit._Widget"],["require","dijit._Templated"],["require","dijit._Contained"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.MenuSeparator"]){_4._hasResource["dijit.MenuSeparator"]=true;_4.provide("dijit.MenuSeparator");_4.require("dijit._Widget");_4.require("dijit._Templated");_4.require("dijit._Contained");_4.declare("dijit.MenuSeparator",[_5._Widget,_5._Templated,_5._Contained],{templateString:_4.cache("dijit","templates/MenuSeparator.html","<tr class=\"dijitMenuSeparator\">\n\t<td colspan=\"4\">\n\t\t<div class=\"dijitMenuSeparatorTop\"></div>\n\t\t<div class=\"dijitMenuSeparatorBottom\"></div>\n\t</td>\n</tr>\n"),postCreate:function(){_4.setSelectable(this.domNode,false);},isFocusable:function(){return false;}});}}};});