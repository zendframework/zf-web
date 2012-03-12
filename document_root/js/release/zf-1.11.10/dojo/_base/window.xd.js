/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo._base.window"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo._base.window"]){_4._hasResource["dojo._base.window"]=true;_4.provide("dojo._base.window");_4.doc=window["document"]||null;_4.body=function(){return _4.doc.body||_4.doc.getElementsByTagName("body")[0];};_4.setContext=function(_7,_8){_4.global=_7;_4.doc=_8;};_4.withGlobal=function(_9,_a,_b,_c){var _d=_4.global;try{_4.global=_9;return _4.withDoc.call(null,_9.document,_a,_b,_c);}finally{_4.global=_d;}};_4.withDoc=function(_e,_f,_10,_11){var _12=_4.doc,_13=_4._bodyLtr,_14=_4.isQuirks;try{_4.doc=_e;delete _4._bodyLtr;_4.isQuirks=_4.doc.compatMode=="BackCompat";if(_10&&typeof _f=="string"){_f=_10[_f];}return _f.apply(_10,_11||[]);}finally{_4.doc=_12;delete _4._bodyLtr;if(_13!==undefined){_4._bodyLtr=_13;}_4.isQuirks=_14;}};}}};});