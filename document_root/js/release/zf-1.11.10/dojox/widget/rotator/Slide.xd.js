/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.widget.rotator.Slide"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.widget.rotator.Slide"]){_4._hasResource["dojox.widget.rotator.Slide"]=true;_4.provide("dojox.widget.rotator.Slide");(function(d){var _7=0,_8=1,UP=2,_9=3;function _a(_b,_c){var _d=_c.node=_c.next.node,r=_c.rotatorBox,m=_b%2,s=(m?r.w:r.h)*(_b<2?-1:1);d.style(_d,{display:"",zIndex:(d.style(_c.current.node,"zIndex")||1)+1});if(!_c.properties){_c.properties={};}_c.properties[m?"left":"top"]={start:s,end:0};return d.animateProperty(_c);};d.mixin(_6.widget.rotator,{slideDown:function(_e){return _a(_7,_e);},slideRight:function(_f){return _a(_8,_f);},slideUp:function(_10){return _a(UP,_10);},slideLeft:function(_11){return _a(_9,_11);}});})(_4);}}};});