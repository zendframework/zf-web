/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.fx.scroll"],["require","dojox.fx._core"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.fx.scroll"]){_4._hasResource["dojox.fx.scroll"]=true;_4.provide("dojox.fx.scroll");_4.experimental("dojox.fx.scroll");_4.require("dojox.fx._core");_6.fx.smoothScroll=function(_7){if(!_7.target){_7.target=_4.coords(_7.node,true);}var _8=_4[(_4.isIE?"isObject":"isFunction")](_7["win"].scrollTo);var _9=(_8)?(function(_a){_7.win.scrollTo(_a[0],_a[1]);}):(function(_b){_7.win.scrollLeft=_b[0];_7.win.scrollTop=_b[1];});var _c=new _4.Animation(_4.mixin({beforeBegin:function(){if(this.curve){delete this.curve;}var _d=_8?_4._docScroll():{x:_7.win.scrollLeft,y:_7.win.scrollTop};_c.curve=new _6.fx._Line([_d.x,_d.y],[_7.target.x,_7.target.y]);},onAnimate:_9},_7));return _c;};}}};});