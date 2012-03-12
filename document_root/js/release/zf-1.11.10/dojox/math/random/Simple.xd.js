/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.math.random.Simple"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.math.random.Simple"]){_4._hasResource["dojox.math.random.Simple"]=true;_4.provide("dojox.math.random.Simple");_4.declare("dojox.math.random.Simple",null,{destroy:function(){},nextBytes:function(_7){for(var i=0,l=_7.length;i<l;++i){_7[i]=Math.floor(256*Math.random());}}});}}};});