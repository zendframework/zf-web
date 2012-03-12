/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["require","dojox.analytics._base"],["provide","dojox.analytics.plugins.mouseClick"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.analytics.plugins.mouseClick"]){_4._hasResource["dojox.analytics.plugins.mouseClick"]=true;_4.require("dojox.analytics._base");_4.provide("dojox.analytics.plugins.mouseClick");_6.analytics.plugins.mouseClick=new (function(){this.addData=_4.hitch(_6.analytics,"addData","mouseClick");this.onClick=function(e){this.addData(this.trimEvent(e));};_4.connect(_4.doc,"onclick",this,"onClick");this.trimEvent=function(e){var t={};for(var i in e){switch(i){case "target":case "originalTarget":case "explicitOriginalTarget":var _7=["id","className","nodeName","localName","href","spellcheck","lang"];t[i]={};for(var j=0;j<_7.length;j++){if(e[i][_7[j]]){if(_7[j]=="text"||_7[j]=="textContent"){if((e[i]["localName"]!="HTML")&&(e[i]["localName"]!="BODY")){t[i][_7[j]]=e[i][_7[j]].substr(0,50);}}else{t[i][_7[j]]=e[i][_7[j]];}}}break;case "clientX":case "clientY":case "pageX":case "pageY":case "screenX":case "screenY":t[i]=e[i];break;}}return t;};})();}}};});