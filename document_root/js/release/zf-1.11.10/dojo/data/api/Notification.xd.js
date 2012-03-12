/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.data.api.Notification"],["require","dojo.data.api.Read"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.data.api.Notification"]){_4._hasResource["dojo.data.api.Notification"]=true;_4.provide("dojo.data.api.Notification");_4.require("dojo.data.api.Read");_4.declare("dojo.data.api.Notification",_4.data.api.Read,{getFeatures:function(){return {"dojo.data.api.Read":true,"dojo.data.api.Notification":true};},onSet:function(_7,_8,_9,_a){throw new Error("Unimplemented API: dojo.data.api.Notification.onSet");},onNew:function(_b,_c){throw new Error("Unimplemented API: dojo.data.api.Notification.onNew");},onDelete:function(_d){throw new Error("Unimplemented API: dojo.data.api.Notification.onDelete");}});}}};});