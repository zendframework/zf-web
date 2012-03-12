/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.data.JsonQueryRestStore"],["require","dojox.data.JsonRestStore"],["require","dojox.data.util.JsonQuery"],["requireIf",!!_3.data.ClientFilter,"dojox.json.query"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.data.JsonQueryRestStore"]){_4._hasResource["dojox.data.JsonQueryRestStore"]=true;_4.provide("dojox.data.JsonQueryRestStore");_4.require("dojox.data.JsonRestStore");_4.require("dojox.data.util.JsonQuery");_4.requireIf(!!_6.data.ClientFilter,"dojox.json.query");_4.declare("dojox.data.JsonQueryRestStore",[_6.data.JsonRestStore,_6.data.util.JsonQuery],{matchesQuery:function(_7,_8){return _7.__id&&(_7.__id.indexOf("#")==-1)&&this.inherited(arguments);}});}}};});