/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.form.TimeTextBox"],["require","dijit._TimePicker"],["require","dijit.form._DateTimeTextBox"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.form.TimeTextBox"]){_4._hasResource["dijit.form.TimeTextBox"]=true;_4.provide("dijit.form.TimeTextBox");_4.require("dijit._TimePicker");_4.require("dijit.form._DateTimeTextBox");_4.declare("dijit.form.TimeTextBox",_5.form._DateTimeTextBox,{baseClass:"dijitTextBox dijitTimeTextBox",popupClass:"dijit._TimePicker",_selector:"time",value:new Date("")});}}};});