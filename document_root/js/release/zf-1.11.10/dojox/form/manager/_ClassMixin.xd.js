/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.form.manager._ClassMixin"],["require","dojox.form.manager._Mixin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.form.manager._ClassMixin"]){_4._hasResource["dojox.form.manager._ClassMixin"]=true;_4.provide("dojox.form.manager._ClassMixin");_4.require("dojox.form.manager._Mixin");(function(){var fm=_6.form.manager,aa=fm.actionAdapter,ia=fm.inspectorAdapter;_4.declare("dojox.form.manager._ClassMixin",null,{gatherClassState:function(_7,_8){var _9=this.inspect(ia(function(_a,_b){return _4.hasClass(_b,_7);}),_8);return _9;},addClass:function(_c,_d){this.inspect(aa(function(_e,_f){_4.addClass(_f,_c);}),_d);return this;},removeClass:function(_10,_11){this.inspect(aa(function(_12,_13){_4.removeClass(_13,_10);}),_11);return this;}});})();}}};});