/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.layout.LinkPane"],["require","dijit.layout.ContentPane"],["require","dijit._Templated"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.layout.LinkPane"]){_4._hasResource["dijit.layout.LinkPane"]=true;_4.provide("dijit.layout.LinkPane");_4.require("dijit.layout.ContentPane");_4.require("dijit._Templated");_4.declare("dijit.layout.LinkPane",[_5.layout.ContentPane,_5._Templated],{templateString:"<div class=\"dijitLinkPane\" dojoAttachPoint=\"containerNode\"></div>",postMixInProperties:function(){if(this.srcNodeRef){this.title+=this.srcNodeRef.innerHTML;}this.inherited(arguments);},_fillContent:function(_7){}});}}};});