/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.cometd.ack"],["require","dojox.cometd._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.cometd.ack"]){_4._hasResource["dojox.cometd.ack"]=true;_4.provide("dojox.cometd.ack");_4.require("dojox.cometd._base");_6.cometd._ack=new function(){var _7=false;var _8=-1;this._in=function(_9){if(_9.channel=="/meta/handshake"){_7=_9.ext&&_9.ext.ack;}else{if(_7&&_9.channel=="/meta/connect"&&_9.ext&&_9.ext.ack&&_9.successful){var _a=parseInt(_9.ext.ack);_8=_a;}}return _9;};this._out=function(_b){if(_b.channel=="/meta/handshake"){if(!_b.ext){_b.ext={};}_b.ext.ack=_6.cometd.ackEnabled;_8=-1;}if(_7&&_b.channel=="/meta/connect"){if(!_b.ext){_b.ext={};}_b.ext.ack=_8;}return _b;};};_6.cometd._extendInList.push(_4.hitch(_6.cometd._ack,"_in"));_6.cometd._extendOutList.push(_4.hitch(_6.cometd._ack,"_out"));_6.cometd.ackEnabled=true;}}};});