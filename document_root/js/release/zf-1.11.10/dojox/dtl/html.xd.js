/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.html"],["require","dojox.dtl.dom"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.html"]){_4._hasResource["dojox.dtl.html"]=true;_4.provide("dojox.dtl.html");_4.deprecated("dojox.dtl.html","All packages and classes in dojox.dtl that start with Html or html have been renamed to Dom or dom");_4.require("dojox.dtl.dom");_6.dtl.HtmlTemplate=_6.dtl.DomTemplate;}}};});