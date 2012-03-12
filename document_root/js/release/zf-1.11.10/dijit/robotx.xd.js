/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.robotx"],["require","dijit.robot"],["require","dojo.robotx"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.robotx"]){_4._hasResource["dijit.robotx"]=true;_4.provide("dijit.robotx");_4.require("dijit.robot");_4.require("dojo.robotx");_4.experimental("dijit.robotx");(function(){var _7=doh.robot._updateDocument;_4.mixin(doh.robot,{_updateDocument:function(){_7();var _8=_4.global;if(_8["dijit"]){_5=_8.dijit;}}});})();}}};});