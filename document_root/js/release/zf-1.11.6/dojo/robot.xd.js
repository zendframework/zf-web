/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.robot"],["require","doh.robot"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.robot"]){_4._hasResource["dojo.robot"]=true;_4.provide("dojo.robot");_4.experimental("dojo.robot");_4.require("doh.robot");(function(){_4.mixin(doh.robot,{_resolveNode:function(n){if(typeof n=="function"){n=n();}return n?_4.byId(n):null;},_scrollIntoView:function(_7){_7.scrollIntoView(false);},_position:function(n){return _4.position(n,false);},scrollIntoView:function(_8,_9){doh.robot.sequence(function(){doh.robot._scrollIntoView(doh.robot._resolveNode(_8));},_9);},mouseMoveAt:function(_a,_b,_c,_d,_e){doh.robot._assertRobot();_c=_c||100;this.sequence(function(){_a=doh.robot._resolveNode(_a);doh.robot._scrollIntoView(_a);var _f=doh.robot._position(_a);if(_e===undefined){_d=_f.w/2;_e=_f.h/2;}var x=_f.x+_d;var y=_f.y+_e;doh.robot._mouseMove(x,y,false,_c);},_b,_c);}});})();}}};});