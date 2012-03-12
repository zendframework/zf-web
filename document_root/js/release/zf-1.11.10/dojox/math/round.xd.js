/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.math.round"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.math.round"]){_4._hasResource["dojox.math.round"]=true;_4.provide("dojox.math.round");_4.experimental("dojox.math.round");_6.math.round=function(_7,_8,_9){var _a=Math.log(Math.abs(_7))/Math.log(10);var _b=10/(_9||10);var _c=Math.pow(10,-15+_a);return (_b*(+_7+(_7>0?_c:-_c))).toFixed(_8)/_b;};if((0.9).toFixed()==0){(function(){var _d=_6.math.round;_6.math.round=function(v,p,m){var d=Math.pow(10,-p||0),a=Math.abs(v);if(!v||a>=d||a*Math.pow(10,p+1)<5){d=0;}return _d(v,p,m)+(v>0?d:-d);};})();}}}};});