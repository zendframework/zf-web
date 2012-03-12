/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.widget.rotator.Wipe"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.widget.rotator.Wipe"]){_4._hasResource["dojox.widget.rotator.Wipe"]=true;_4.provide("dojox.widget.rotator.Wipe");(function(d){var _7=2,_8=3,UP=0,_9=1;function _a(_b,w,h,x){var a=[0,w,0,0];if(_b==_8){a=[0,w,h,w];}else{if(_b==UP){a=[h,w,h,0];}else{if(_b==_9){a=[0,0,h,0];}}}if(x!=null){a[_b]=_b==_7||_b==_9?x:(_b%2?w:h)-x;}return a;};function _c(n,_d,w,h,x){d.style(n,"clip",_d==null?"auto":"rect("+_a(_d,w,h,x).join("px,")+"px)");};function _e(_f,_10){var _11=_10.next.node,w=_10.rotatorBox.w,h=_10.rotatorBox.h;d.style(_11,{display:"",zIndex:(d.style(_10.current.node,"zIndex")||1)+1});_c(_11,_f,w,h);return new d.Animation(d.mixin({node:_11,curve:[0,_f%2?w:h],onAnimate:function(x){_c(_11,_f,w,h,parseInt(x));}},_10));};d.mixin(_6.widget.rotator,{wipeDown:function(_12){return _e(_7,_12);},wipeRight:function(_13){return _e(_8,_13);},wipeUp:function(_14){return _e(UP,_14);},wipeLeft:function(_15){return _e(_9,_15);}});})(_4);}}};});