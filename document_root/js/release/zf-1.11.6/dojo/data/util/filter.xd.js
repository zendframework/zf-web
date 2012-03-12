/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.data.util.filter"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.data.util.filter"]){_4._hasResource["dojo.data.util.filter"]=true;_4.provide("dojo.data.util.filter");_4.data.util.filter.patternToRegExp=function(_7,_8){var _9="^";var c=null;for(var i=0;i<_7.length;i++){c=_7.charAt(i);switch(c){case "\\":_9+=c;i++;_9+=_7.charAt(i);break;case "*":_9+=".*";break;case "?":_9+=".";break;case "$":case "^":case "/":case "+":case ".":case "|":case "(":case ")":case "{":case "}":case "[":case "]":_9+="\\";default:_9+=c;}}_9+="$";if(_8){return new RegExp(_9,"mi");}else{return new RegExp(_9,"m");}};}}};});