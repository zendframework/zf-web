/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.scaler.primitive"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.scaler.primitive"]){_4._hasResource["dojox.charting.scaler.primitive"]=true;_4.provide("dojox.charting.scaler.primitive");_6.charting.scaler.primitive={buildScaler:function(_7,_8,_9,_a){return {bounds:{lower:_7,upper:_8,from:_7,to:_8,scale:_9/(_8-_7),span:_9},scaler:_6.charting.scaler.primitive};},buildTicks:function(_b,_c){return {major:[],minor:[],micro:[]};},getTransformerFromModel:function(_d){var _e=_d.bounds.from,_f=_d.bounds.scale;return function(x){return (x-_e)*_f;};},getTransformerFromPlot:function(_10){var _11=_10.bounds.from,_12=_10.bounds.scale;return function(x){return x/_12+_11;};}};}}};});