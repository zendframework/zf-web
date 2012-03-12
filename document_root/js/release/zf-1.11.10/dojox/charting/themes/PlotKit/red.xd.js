/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.themes.PlotKit.red"],["require","dojox.charting.Theme"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.themes.PlotKit.red"]){_4._hasResource["dojox.charting.themes.PlotKit.red"]=true;_4.provide("dojox.charting.themes.PlotKit.red");_4.require("dojox.charting.Theme");(function(){var _7=_6.charting;_7.themes.PlotKit.red=new _7.Theme({chart:{stroke:null,fill:"white"},plotarea:{stroke:null,fill:"#f5e6e6"},axis:{stroke:{color:"#fff",width:2},line:{color:"#fff",width:1},majorTick:{color:"#fff",width:2,length:12},minorTick:{color:"#fff",width:1,length:8},font:"normal normal normal 8pt Tahoma",fontColor:"#999"},series:{outline:{width:1,color:"#fff"},stroke:{width:2,color:"#666"},fill:new _4.Color([102,102,102,0.8]),font:"normal normal normal 7pt Tahoma",fontColor:"#000"},marker:{stroke:{width:2},fill:"#333",font:"normal normal normal 7pt Tahoma",fontColor:"#000"},colors:[]});_7.themes.PlotKit.red.defineColors({hue:1,saturation:60,low:40,high:88});})();}}};});