/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.Element"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.Element"]){_4._hasResource["dojox.charting.Element"]=true;_4.provide("dojox.charting.Element");_4.declare("dojox.charting.Element",null,{constructor:function(_7){this.chart=_7;this.group=null;this.htmlElements=[];this.dirty=true;},createGroup:function(_8){if(!_8){_8=this.chart.surface;}if(!this.group){this.group=_8.createGroup();}return this;},purgeGroup:function(){this.destroyHtmlElements();if(this.group){this.group.clear();this.group.removeShape();this.group=null;}this.dirty=true;return this;},cleanGroup:function(_9){this.destroyHtmlElements();if(!_9){_9=this.chart.surface;}if(this.group){this.group.clear();}else{this.group=_9.createGroup();}this.dirty=true;return this;},destroyHtmlElements:function(){if(this.htmlElements.length){_4.forEach(this.htmlElements,_4.destroy);this.htmlElements=[];}},destroy:function(){this.purgeGroup();}});}}};});