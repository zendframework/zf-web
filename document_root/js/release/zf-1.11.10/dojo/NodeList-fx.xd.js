/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.NodeList-fx"],["require","dojo.fx"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.NodeList-fx"]){_4._hasResource["dojo.NodeList-fx"]=true;_4.provide("dojo.NodeList-fx");_4.require("dojo.fx");_4.extend(_4.NodeList,{_anim:function(_7,_8,_9){_9=_9||{};return _4.fx.combine(this.map(function(_a){var _b={node:_a};_4.mixin(_b,_9);return _7[_8](_b);}));},wipeIn:function(_c){return this._anim(_4.fx,"wipeIn",_c);},wipeOut:function(_d){return this._anim(_4.fx,"wipeOut",_d);},slideTo:function(_e){return this._anim(_4.fx,"slideTo",_e);},fadeIn:function(_f){return this._anim(_4,"fadeIn",_f);},fadeOut:function(_10){return this._anim(_4,"fadeOut",_10);},animateProperty:function(_11){return this._anim(_4,"animateProperty",_11);},anim:function(_12,_13,_14,_15,_16){var _17=_4.fx.combine(this.map(function(_18){return _4.animateProperty({node:_18,properties:_12,duration:_13||350,easing:_14});}));if(_15){_4.connect(_17,"onEnd",_15);}return _17.play(_16||0);}});}}};});