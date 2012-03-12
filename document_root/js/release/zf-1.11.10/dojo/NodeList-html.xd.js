/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.NodeList-html"],["require","dojo.html"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.NodeList-html"]){_4._hasResource["dojo.NodeList-html"]=true;_4.provide("dojo.NodeList-html");_4.require("dojo.html");_4.extend(_4.NodeList,{html:function(_7,_8){var _9=new _4.html._ContentSetter(_8||{});this.forEach(function(_a){_9.node=_a;_9.set(_7);_9.tearDown();});return this;}});}}};});