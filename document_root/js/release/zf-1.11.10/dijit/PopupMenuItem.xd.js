/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.PopupMenuItem"],["require","dijit.MenuItem"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.PopupMenuItem"]){_4._hasResource["dijit.PopupMenuItem"]=true;_4.provide("dijit.PopupMenuItem");_4.require("dijit.MenuItem");_4.declare("dijit.PopupMenuItem",_5.MenuItem,{_fillContent:function(){if(this.srcNodeRef){var _7=_4.query("*",this.srcNodeRef);_5.PopupMenuItem.superclass._fillContent.call(this,_7[0]);this.dropDownContainer=this.srcNodeRef;}},startup:function(){if(this._started){return;}this.inherited(arguments);if(!this.popup){var _8=_4.query("[widgetId]",this.dropDownContainer)[0];this.popup=_5.byNode(_8);}_4.body().appendChild(this.popup.domNode);this.popup.domNode.style.display="none";if(this.arrowWrapper){_4.style(this.arrowWrapper,"visibility","");}_5.setWaiState(this.focusNode,"haspopup","true");},destroyDescendants:function(){if(this.popup){if(!this.popup._destroyed){this.popup.destroyRecursive();}delete this.popup;}this.inherited(arguments);}});}}};});