/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.rpc.Client"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.rpc.Client"]){_4._hasResource["dojox.rpc.Client"]=true;_4.provide("dojox.rpc.Client");(function(){_4._defaultXhr=_4.xhr;_4.xhr=function(_7,_8){var _9=_8.headers=_8.headers||{};_9["Client-Id"]=_6.rpc.Client.clientId;_9["Seq-Id"]=_6._reqSeqId=(_6._reqSeqId||0)+1;return _4._defaultXhr.apply(_4,arguments);};})();_6.rpc.Client.clientId=(Math.random()+"").substring(2,14)+(new Date().getTime()+"").substring(8,13);}}};});