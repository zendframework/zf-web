/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.filter.misc"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.filter.misc"]){_4._hasResource["dojox.dtl.filter.misc"]=true;_4.provide("dojox.dtl.filter.misc");_4.mixin(_6.dtl.filter.misc,{filesizeformat:function(_7){_7=parseFloat(_7);if(_7<1024){return (_7==1)?_7+" byte":_7+" bytes";}else{if(_7<1024*1024){return (_7/1024).toFixed(1)+" KB";}else{if(_7<1024*1024*1024){return (_7/1024/1024).toFixed(1)+" MB";}}}return (_7/1024/1024/1024).toFixed(1)+" GB";},pluralize:function(_8,_9){_9=_9||"s";if(_9.indexOf(",")==-1){_9=","+_9;}var _a=_9.split(",");if(_a.length>2){return "";}var _b=_a[0];var _c=_a[1];if(parseInt(_8,10)!=1){return _c;}return _b;},_phone2numeric:{a:2,b:2,c:2,d:3,e:3,f:3,g:4,h:4,i:4,j:5,k:5,l:5,m:6,n:6,o:6,p:7,r:7,s:7,t:8,u:8,v:8,w:9,x:9,y:9},phone2numeric:function(_d){var dm=_6.dtl.filter.misc;_d=_d+"";var _e="";for(var i=0;i<_d.length;i++){var _f=_d.charAt(i).toLowerCase();(dm._phone2numeric[_f])?_e+=dm._phone2numeric[_f]:_e+=_d.charAt(i);}return _e;},pprint:function(_10){return _4.toJson(_10);}});}}};});