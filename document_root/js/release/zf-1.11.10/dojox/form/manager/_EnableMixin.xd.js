/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.form.manager._EnableMixin"],["require","dojox.form.manager._Mixin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.form.manager._EnableMixin"]){_4._hasResource["dojox.form.manager._EnableMixin"]=true;_4.provide("dojox.form.manager._EnableMixin");_4.require("dojox.form.manager._Mixin");(function(){var fm=_6.form.manager,aa=fm.actionAdapter,ia=fm.inspectorAdapter;_4.declare("dojox.form.manager._EnableMixin",null,{gatherEnableState:function(_7){var _8=this.inspectFormWidgets(ia(function(_9,_a){return !_a.attr("disabled");}),_7);if(this.inspectFormNodes){_4.mixin(_8,this.inspectFormNodes(ia(function(_b,_c){return !_4.attr(_c,"disabled");}),_7));}return _8;},enable:function(_d,_e){if(arguments.length<2||_e===undefined){_e=true;}this.inspectFormWidgets(aa(function(_f,_10,_11){_10.attr("disabled",!_11);}),_d,_e);if(this.inspectFormNodes){this.inspectFormNodes(aa(function(_12,_13,_14){_4.attr(_13,"disabled",!_14);}),_d,_e);}return this;},disable:function(_15){var _16=this.gatherEnableState();this.enable(_15,false);return _16;}});})();}}};});