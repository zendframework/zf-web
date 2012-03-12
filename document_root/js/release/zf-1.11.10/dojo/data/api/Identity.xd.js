/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.data.api.Identity"],["require","dojo.data.api.Read"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.data.api.Identity"]){_4._hasResource["dojo.data.api.Identity"]=true;_4.provide("dojo.data.api.Identity");_4.require("dojo.data.api.Read");_4.declare("dojo.data.api.Identity",_4.data.api.Read,{getFeatures:function(){return {"dojo.data.api.Read":true,"dojo.data.api.Identity":true};},getIdentity:function(_7){throw new Error("Unimplemented API: dojo.data.api.Identity.getIdentity");var _8=null;return _8;},getIdentityAttributes:function(_9){throw new Error("Unimplemented API: dojo.data.api.Identity.getIdentityAttributes");return null;},fetchItemByIdentity:function(_a){if(!this.isItemLoaded(_a.item)){throw new Error("Unimplemented API: dojo.data.api.Identity.fetchItemByIdentity");}}});}}};});