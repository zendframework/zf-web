/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.oo.general"],["require","dojox.lang.oo.Decorator"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.oo.general"]){_4._hasResource["dojox.lang.oo.general"]=true;_4.provide("dojox.lang.oo.general");_4.require("dojox.lang.oo.Decorator");(function(){var oo=_6.lang.oo,md=oo.makeDecorator,_7=oo.general,_8=_4.isFunction;_7.augment=md(function(_9,_a,_b){return typeof _b=="undefined"?_a:_b;});_7.override=md(function(_c,_d,_e){return typeof _e!="undefined"?_d:_e;});_7.shuffle=md(function(_f,_10,_11){return _8(_11)?function(){return _11.apply(this,_10.apply(this,arguments));}:_11;});_7.wrap=md(function(_12,_13,_14){return function(){return _13.call(this,_14,arguments);};});_7.tap=md(function(_15,_16,_17){return function(){_16.apply(this,arguments);return this;};});_7.before=md(function(_18,_19,_1a){return _8(_1a)?function(){_19.apply(this,arguments);return _1a.apply(this,arguments);}:_19;});_7.after=md(function(_1b,_1c,_1d){return _8(_1d)?function(){_1d.apply(this,arguments);return _1c.apply(this,arguments);}:_1c;});})();}}};});