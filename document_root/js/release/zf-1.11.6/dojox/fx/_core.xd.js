/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.fx._core"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.fx._core"]){_4._hasResource["dojox.fx._core"]=true;_4.provide("dojox.fx._core");_6.fx._Line=function(_7,_8){this.start=_7;this.end=_8;var _9=_4.isArray(_7),d=(_9?[]:_8-_7);if(_9){_4.forEach(this.start,function(s,i){d[i]=this.end[i]-s;},this);this.getValue=function(n){var _a=[];_4.forEach(this.start,function(s,i){_a[i]=(d[i]*n)+s;},this);return _a;};}else{this.getValue=function(n){return (d*n)+this.start;};}};}}};});