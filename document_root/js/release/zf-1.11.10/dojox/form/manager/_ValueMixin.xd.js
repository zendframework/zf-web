/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.form.manager._ValueMixin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.form.manager._ValueMixin"]){_4._hasResource["dojox.form.manager._ValueMixin"]=true;_4.provide("dojox.form.manager._ValueMixin");_4.declare("dojox.form.manager._ValueMixin",null,{elementValue:function(_7,_8){if(_7 in this.formWidgets){return this.formWidgetValue(_7,_8);}if(this.formNodes&&_7 in this.formNodes){return this.formNodeValue(_7,_8);}return this.formPointValue(_7,_8);},gatherFormValues:function(_9){var _a=this.inspectFormWidgets(function(_b){return this.formWidgetValue(_b);},_9);if(this.inspectFormNodes){_4.mixin(_a,this.inspectFormNodes(function(_c){return this.formNodeValue(_c);},_9));}_4.mixin(_a,this.inspectAttachedPoints(function(_d){return this.formPointValue(_d);},_9));return _a;},setFormValues:function(_e){if(_e){this.inspectFormWidgets(function(_f,_10,_11){this.formWidgetValue(_f,_11);},_e);if(this.inspectFormNodes){this.inspectFormNodes(function(_12,_13,_14){this.formNodeValue(_12,_14);},_e);}this.inspectAttachedPoints(function(_15,_16,_17){this.formPointValue(_15,_17);},_e);}return this;}});}}};});