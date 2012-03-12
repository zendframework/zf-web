/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.form.RadioStack"],["require","dojox.form.CheckedMultiSelect"],["require","dojox.form._SelectStackMixin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.form.RadioStack"]){_4._hasResource["dojox.form.RadioStack"]=true;_4.provide("dojox.form.RadioStack");_4.require("dojox.form.CheckedMultiSelect");_4.require("dojox.form._SelectStackMixin");_4.declare("dojox.form.RadioStack",[_6.form.CheckedMultiSelect,_6.form._SelectStackMixin],{});}}};});