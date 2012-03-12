/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.regexp"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.regexp"]){_4._hasResource["dojo.regexp"]=true;_4.provide("dojo.regexp");_4.regexp.escapeString=function(_7,_8){return _7.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g,function(ch){if(_8&&_8.indexOf(ch)!=-1){return ch;}return "\\"+ch;});};_4.regexp.buildGroupRE=function(_9,re,_a){if(!(_9 instanceof Array)){return re(_9);}var b=[];for(var i=0;i<_9.length;i++){b.push(re(_9[i]));}return _4.regexp.group(b.join("|"),_a);};_4.regexp.group=function(_b,_c){return "("+(_c?"?:":"")+_b+")";};}}};});