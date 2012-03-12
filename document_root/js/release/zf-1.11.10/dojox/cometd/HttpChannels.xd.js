/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.cometd.HttpChannels"],["require","dojox.io.httpParse"],["require","dojox.cometd.RestChannels"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.cometd.HttpChannels"]){_4._hasResource["dojox.cometd.HttpChannels"]=true;_4.provide("dojox.cometd.HttpChannels");_4.require("dojox.io.httpParse");_4.require("dojox.cometd.RestChannels");_6.cometd.HttpChannels=_6.cometd.RestChannels;}}};});