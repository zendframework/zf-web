/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.jaxer"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.jaxer"]){_4._hasResource["dojo.jaxer"]=true;_4.provide("dojo.jaxer");if(typeof print=="function"){console.debug=Jaxer.Log.debug;console.warn=Jaxer.Log.warn;console.error=Jaxer.Log.error;console.info=Jaxer.Log.info;console.log=Jaxer.Log.warn;}onserverload=_4._loadInit;}}};});