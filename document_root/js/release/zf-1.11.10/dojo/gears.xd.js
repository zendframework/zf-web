/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.gears"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.gears"]){_4._hasResource["dojo.gears"]=true;_4.provide("dojo.gears");_4.gears._gearsObject=function(){var _7;var _8;var _9=_4.getObject("google.gears");if(_9){return _9;}if(typeof GearsFactory!="undefined"){_7=new GearsFactory();}else{if(_4.isIE){try{_7=new ActiveXObject("Gears.Factory");}catch(e){}}else{if(navigator.mimeTypes["application/x-googlegears"]){_7=document.createElement("object");_7.setAttribute("type","application/x-googlegears");_7.setAttribute("width",0);_7.setAttribute("height",0);_7.style.display="none";document.documentElement.appendChild(_7);}}}if(!_7){return null;}_4.setObject("google.gears.factory",_7);return _4.getObject("google.gears");};_4.gears.available=(!!_4.gears._gearsObject())||0;}}};});