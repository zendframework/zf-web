/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.HtmlInline"],["require","dojox.dtl.DomInline"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.HtmlInline"]){_4._hasResource["dojox.dtl.HtmlInline"]=true;_4.provide("dojox.dtl.HtmlInline");_4.require("dojox.dtl.DomInline");_4.deprecated("dojox.dtl.html","All packages and classes in dojox.dtl that start with Html or html have been renamed to Dom or dom");_6.dtl.HtmlInline=_6.dtl.DomInline;_6.dtl.HtmlInline.prototype.declaredClass="dojox.dtl.HtmlInline";}}};});