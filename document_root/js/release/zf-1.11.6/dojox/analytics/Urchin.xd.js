/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.analytics.Urchin"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.analytics.Urchin"]){_4._hasResource["dojox.analytics.Urchin"]=true;_4.provide("dojox.analytics.Urchin");_4.declare("dojox.analytics.Urchin",null,{acct:"",constructor:function(_7){this.tracker=null;_4.mixin(this,_7);this.acct=this.acct||_4.config.urchin;var re=/loaded|complete/,_8=("https:"==_4.doc.location.protocol)?"https://ssl.":"http://www.",h=_4.doc.getElementsByTagName("head")[0],n=_4.create("script",{src:_8+"google-analytics.com/ga.js"},h);n.onload=n.onreadystatechange=_4.hitch(this,function(e){if(e&&e.type=="load"||re.test(n.readyState)){n.onload=n.onreadystatechange=null;this._gotGA();h.removeChild(n);}});},_gotGA:function(){this.tracker=_gat._getTracker(this.acct);this.GAonLoad.apply(this,arguments);},GAonLoad:function(){this.trackPageView();},trackPageView:function(_9){this.tracker._trackPageview.apply(this,arguments);}});}}};});