/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.tag.date"],["require","dojox.dtl._base"],["require","dojox.dtl.utils.date"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.tag.date"]){_4._hasResource["dojox.dtl.tag.date"]=true;_4.provide("dojox.dtl.tag.date");_4.require("dojox.dtl._base");_4.require("dojox.dtl.utils.date");_6.dtl.tag.date.NowNode=function(_7,_8){this._format=_7;this.format=new _6.dtl.utils.date.DateFormat(_7);this.contents=_8;};_4.extend(_6.dtl.tag.date.NowNode,{render:function(_9,_a){this.contents.set(this.format.format(new Date()));return this.contents.render(_9,_a);},unrender:function(_b,_c){return this.contents.unrender(_b,_c);},clone:function(_d){return new this.constructor(this._format,this.contents.clone(_d));}});_6.dtl.tag.date.now=function(_e,_f){var _10=_f.split_contents();if(_10.length!=2){throw new Error("'now' statement takes one argument");}return new _6.dtl.tag.date.NowNode(_10[1].slice(1,-1),_e.create_text_node());};}}};});