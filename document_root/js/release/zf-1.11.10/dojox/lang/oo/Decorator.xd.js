/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.oo.Decorator"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.oo.Decorator"]){_4._hasResource["dojox.lang.oo.Decorator"]=true;_4.provide("dojox.lang.oo.Decorator");(function(){var oo=_6.lang.oo,D=oo.Decorator=function(_7,_8){this.value=_7;this.decorator=typeof _8=="object"?function(){return _8.exec.apply(_8,arguments);}:_8;};oo.makeDecorator=function(_9){return function(_a){return new D(_a,_9);};};})();}}};});