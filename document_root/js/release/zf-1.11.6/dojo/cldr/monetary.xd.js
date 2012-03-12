/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.cldr.monetary"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.cldr.monetary"]){_4._hasResource["dojo.cldr.monetary"]=true;_4.provide("dojo.cldr.monetary");_4.cldr.monetary.getData=function(_7){var _8={ADP:0,BHD:3,BIF:0,BYR:0,CLF:0,CLP:0,DJF:0,ESP:0,GNF:0,IQD:3,ITL:0,JOD:3,JPY:0,KMF:0,KRW:0,KWD:3,LUF:0,LYD:3,MGA:0,MGF:0,OMR:3,PYG:0,RWF:0,TND:3,TRL:0,VUV:0,XAF:0,XOF:0,XPF:0};var _9={CHF:5};var _a=_8[_7],_b=_9[_7];if(typeof _a=="undefined"){_a=2;}if(typeof _b=="undefined"){_b=0;}return {places:_a,round:_b};};}}};});