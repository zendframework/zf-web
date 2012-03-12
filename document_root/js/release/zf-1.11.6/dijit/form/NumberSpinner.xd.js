/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.form.NumberSpinner"],["require","dijit.form._Spinner"],["require","dijit.form.NumberTextBox"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.form.NumberSpinner"]){_4._hasResource["dijit.form.NumberSpinner"]=true;_4.provide("dijit.form.NumberSpinner");_4.require("dijit.form._Spinner");_4.require("dijit.form.NumberTextBox");_4.declare("dijit.form.NumberSpinner",[_5.form._Spinner,_5.form.NumberTextBoxMixin],{adjust:function(_7,_8){var tc=this.constraints,v=isNaN(_7),_9=!isNaN(tc.max),_a=!isNaN(tc.min);if(v&&_8!=0){_7=(_8>0)?_a?tc.min:_9?tc.max:0:_9?this.constraints.max:_a?tc.min:0;}var _b=_7+_8;if(v||isNaN(_b)){return _7;}if(_9&&(_b>tc.max)){_b=tc.max;}if(_a&&(_b<tc.min)){_b=tc.min;}return _b;},_onKeyPress:function(e){if((e.charOrCode==_4.keys.HOME||e.charOrCode==_4.keys.END)&&!(e.ctrlKey||e.altKey||e.metaKey)&&typeof this.attr("value")!="undefined"){var _c=this.constraints[(e.charOrCode==_4.keys.HOME?"min":"max")];if(_c){this._setValueAttr(_c,true);}_4.stopEvent(e);}}});}}};});