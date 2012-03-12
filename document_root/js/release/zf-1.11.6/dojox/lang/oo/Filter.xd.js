/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.oo.Filter"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.oo.Filter"]){_4._hasResource["dojox.lang.oo.Filter"]=true;_4.provide("dojox.lang.oo.Filter");(function(){var oo=_6.lang.oo,F=oo.Filter=function(_7,_8){this.bag=_7;this.filter=typeof _8=="object"?function(){return _8.exec.apply(_8,arguments);}:_8;},_9=function(_a){this.map=_a;};_9.prototype.exec=function(_b){return this.map.hasOwnProperty(_b)?this.map[_b]:_b;};oo.filter=function(_c,_d){return new F(_c,new _9(_d));};})();}}};});