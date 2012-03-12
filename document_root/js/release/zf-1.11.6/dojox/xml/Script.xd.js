/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.xml.Script"],["require","dojo.parser"],["require","dojox.xml.widgetParser"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.xml.Script"]){_4._hasResource["dojox.xml.Script"]=true;_4.provide("dojox.xml.Script");_4.require("dojo.parser");_4.require("dojox.xml.widgetParser");_4.declare("dojox.xml.Script",null,{constructor:function(_7,_8){_4.parser.instantiate(_6.xml.widgetParser._processScript(_8));}});}}};});