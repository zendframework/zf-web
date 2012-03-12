/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.form.manager._DisplayMixin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.form.manager._DisplayMixin"]){_4._hasResource["dojox.form.manager._DisplayMixin"]=true;_4.provide("dojox.form.manager._DisplayMixin");_4.declare("dojox.form.manager._DisplayMixin",null,{gatherDisplayState:function(_7){var _8=this.inspectAttachedPoints(function(_9,_a){return _4.style(_a,"display")!="none";},_7);return _8;},show:function(_b,_c){if(arguments.length<2){_c=true;}this.inspectAttachedPoints(function(_d,_e,_f){_4.style(_e,"display",_f?"":"none");},_b,_c);return this;},hide:function(_10){return this.show(_10,false);}});}}};});