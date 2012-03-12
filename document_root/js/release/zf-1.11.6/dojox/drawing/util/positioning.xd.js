/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.util.positioning"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.util.positioning"]){_4._hasResource["dojox.drawing.util.positioning"]=true;_4.provide("dojox.drawing.util.positioning");(function(){var _7=4;var _8=20;_6.drawing.util.positioning.label=function(_9,_a){var x=0.5*(_9.x+_a.x);var y=0.5*(_9.y+_a.y);var _b=_6.drawing.util.common.slope(_9,_a);var _c=_7/Math.sqrt(1+_b*_b);if(_a.y>_9.y){_c=-_c;}x+=-_c*_b;y+=_c;var _d=_a.x<_9.x?"end":"start";if(_a.y>_9.y){y-=_8;}return {x:x,y:y,foo:"bar",align:_d};};_6.drawing.util.positioning.angle=function(_e,_f){var x=0.7*_e.x+0.3*_f.x;var y=0.7*_e.y+0.3*_f.y;var _10=_6.drawing.util.common.slope(_e,_f);var _11=_7/Math.sqrt(1+_10*_10);if(_f.x<_e.x){_11=-_11;}x+=-_11*_10;y+=_11;var _12=_f.y>_e.y?"end":"start";y+=_f.x>_e.x?0.5*_8:-0.5*_8;return {x:x,y:y,align:_12};};})();}}};});