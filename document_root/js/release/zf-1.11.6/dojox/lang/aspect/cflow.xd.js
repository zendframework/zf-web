/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.aspect.cflow"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.aspect.cflow"]){_4._hasResource["dojox.lang.aspect.cflow"]=true;_4.provide("dojox.lang.aspect.cflow");(function(){var _7=_6.lang.aspect;_7.cflow=function(_8,_9){if(arguments.length>1&&!(_9 instanceof Array)){_9=[_9];}var _a=_7.getContextStack();for(var i=_a.length-1;i>=0;--i){var c=_a[i];if(_8&&c.instance!=_8){continue;}if(!_9){return true;}var n=c.joinPoint.targetName;for(var j=_9.length-1;j>=0;--j){var m=_9[j];if(m instanceof RegExp){if(m.test(n)){return true;}}else{if(n==m){return true;}}}}return false;};})();}}};});