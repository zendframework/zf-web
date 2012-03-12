/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.functional.sequence"],["require","dojox.lang.functional.lambda"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.functional.sequence"]){_4._hasResource["dojox.lang.functional.sequence"]=true;_4.provide("dojox.lang.functional.sequence");_4.require("dojox.lang.functional.lambda");(function(){var d=_4,df=_6.lang.functional;d.mixin(df,{repeat:function(n,f,z,o){o=o||d.global;f=df.lambda(f);var t=new Array(n),i=1;t[0]=z;for(;i<n;t[i]=z=f.call(o,z),++i){}return t;},until:function(pr,f,z,o){o=o||d.global;f=df.lambda(f);pr=df.lambda(pr);var t=[];for(;!pr.call(o,z);t.push(z),z=f.call(o,z)){}return t;}});})();}}};});