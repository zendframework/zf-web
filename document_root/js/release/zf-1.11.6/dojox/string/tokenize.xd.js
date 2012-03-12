/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.string.tokenize"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.string.tokenize"]){_4._hasResource["dojox.string.tokenize"]=true;_4.provide("dojox.string.tokenize");_6.string.tokenize=function(_7,re,_8,_9){var _a=[];var _b,_c,_d=0;while(_b=re.exec(_7)){_c=_7.slice(_d,re.lastIndex-_b[0].length);if(_c.length){_a.push(_c);}if(_8){if(_4.isOpera){var _e=_b.slice(0);while(_e.length<_b.length){_e.push(null);}_b=_e;}var _f=_8.apply(_9,_b.slice(1).concat(_a.length));if(typeof _f!="undefined"){_a.push(_f);}}_d=re.lastIndex;}_c=_7.slice(_d);if(_c.length){_a.push(_c);}return _a;};}}};});