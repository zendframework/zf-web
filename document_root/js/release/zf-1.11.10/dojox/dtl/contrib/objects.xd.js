/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.contrib.objects"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.contrib.objects"]){_4._hasResource["dojox.dtl.contrib.objects"]=true;_4.provide("dojox.dtl.contrib.objects");_4.mixin(_6.dtl.contrib.objects,{key:function(_7,_8){return _7[_8];}});_6.dtl.register.filters("dojox.dtl.contrib",{"objects":["key"]});}}};});