/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.cometd.timestamp"],["require","dojox.cometd._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.cometd.timestamp"]){_4._hasResource["dojox.cometd.timestamp"]=true;_4.provide("dojox.cometd.timestamp");_4.require("dojox.cometd._base");_6.cometd._extendOutList.push(function(_7){_7.timestamp=new Date().toUTCString();return _7;});}}};});