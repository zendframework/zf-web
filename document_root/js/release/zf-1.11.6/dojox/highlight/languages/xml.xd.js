/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.highlight.languages.xml"],["require","dojox.highlight._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.highlight.languages.xml"]){_4._hasResource["dojox.highlight.languages.xml"]=true;_4.provide("dojox.highlight.languages.xml");_4.require("dojox.highlight._base");(function(){var _7={className:"comment",begin:"<!--",end:"-->"};var _8={className:"attribute",begin:" [a-zA-Z-]+=",end:"^",contains:["value"]};var _9={className:"value",begin:"\"",end:"\""};var dh=_6.highlight,_a=dh.constants;dh.languages.xml={defaultMode:{contains:["pi","comment","cdata","tag"]},case_insensitive:true,modes:[{className:"pi",begin:"<\\?",end:"\\?>",relevance:10},_7,{className:"cdata",begin:"<\\!\\[CDATA\\[",end:"\\]\\]>"},{className:"tag",begin:"</?",end:">",contains:["title","tag_internal"],relevance:1.5},{className:"title",begin:"[A-Za-z:_][A-Za-z0-9\\._:-]+",end:"^",relevance:0},{className:"tag_internal",begin:"^",endsWithParent:true,contains:["attribute"],relevance:0,illegal:"[\\+\\.]"},_8,_9],XML_COMMENT:_7,XML_ATTR:_8,XML_VALUE:_9};})();}}};});