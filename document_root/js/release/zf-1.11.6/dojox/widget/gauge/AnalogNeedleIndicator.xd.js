/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.widget.gauge.AnalogNeedleIndicator"],["require","dojox.widget.AnalogGauge"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.widget.gauge.AnalogNeedleIndicator"]){_4._hasResource["dojox.widget.gauge.AnalogNeedleIndicator"]=true;_4.provide("dojox.widget.gauge.AnalogNeedleIndicator");_4.require("dojox.widget.AnalogGauge");_4.experimental("dojox.widget.gauge.AnalogNeedleIndicator");_4.declare("dojox.widget.gauge.AnalogNeedleIndicator",[_6.widget.gauge.AnalogLineIndicator],{_getShapes:function(){if(!this._gauge){return null;}var x=Math.floor(this.width/2);var _7=this.width*5;var _8=(this.width&1);var _9=[];var _a={color:this.color,width:1};if(this.color.type){_a.color=this.color.colors[0].color;}var xy=(Math.sqrt(2)*(x));_9[0]=this._gauge.surface.createPath().setStroke(_a).setFill(this.color).moveTo(xy,-xy).arcTo((2*x),(2*x),0,0,0,-xy,-xy).lineTo(0,-this.length).closePath();_9[1]=this._gauge.surface.createCircle({cx:0,cy:0,r:this.width}).setStroke({color:this.color}).setFill(this.color);return _9;}});}}};});