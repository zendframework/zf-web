/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.wire.ml.DataStore"],["require","dijit._Widget"],["require","dojox.wire._base"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.wire.ml.DataStore"]){_4._hasResource["dojox.wire.ml.DataStore"]=true;_4.provide("dojox.wire.ml.DataStore");_4.require("dijit._Widget");_4.require("dojox.wire._base");_4.declare("dojox.wire.ml.DataStore",_5._Widget,{storeClass:"",postCreate:function(){this.store=this._createStore();},_createStore:function(){if(!this.storeClass){return null;}var _7=_6.wire._getClass(this.storeClass);if(!_7){return null;}var _8={};var _9=this.domNode.attributes;for(var i=0;i<_9.length;i++){var a=_9.item(i);if(a.specified&&!this[a.nodeName]){_8[a.nodeName]=a.nodeValue;}}return new _7(_8);},getFeatures:function(){return this.store.getFeatures();},fetch:function(_a){return this.store.fetch(_a);},save:function(_b){this.store.save(_b);},newItem:function(_c){return this.store.newItem(_c);},deleteItem:function(_d){return this.store.deleteItem(_d);},revert:function(){return this.store.revert();}});}}};});