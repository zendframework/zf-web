/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.sketch.Slider"],["require","dijit.form.HorizontalSlider"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.sketch.Slider"]){_4._hasResource["dojox.sketch.Slider"]=true;_4.provide("dojox.sketch.Slider");_4.require("dijit.form.HorizontalSlider");_4.declare("dojox.sketch.Slider",_6.sketch._Plugin,{_initButton:function(){this.slider=new _5.form.HorizontalSlider({minimum:5,maximum:100,style:"width:100px;float:right"});this.slider._movable.node.title="Double Click to \"Zoom to Fit\"";this.connect(this.slider,"onChange","_setZoom");this.connect(this.slider.sliderHandle,"ondblclick","_zoomToFit");},_zoomToFit:function(){var r=this.figure.getFit();this.slider.attr("value",this.slider.maximum<r?this.slider.maximum:(this.slider.minimum>r?this.slider.minimum:r));},_setZoom:function(v){if(v&&this.figure){this.figure.zoom(v);}},reset:function(){this.slider.attr("value",this.slider.maximum);this._zoomToFit();},setToolbar:function(t){this._initButton();t.addChild(this.slider);if(!t._reset2Zoom){t._reset2Zoom=true;this.connect(t,"reset","reset");}}});_6.sketch.registerTool("Slider",_6.sketch.Slider);}}};});