/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.functional.util"],["require","dojox.lang.functional.lambda"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.functional.util"]){_4._hasResource["dojox.lang.functional.util"]=true;_4.provide("dojox.lang.functional.util");_4.require("dojox.lang.functional.lambda");(function(){var df=_6.lang.functional;_4.mixin(df,{inlineLambda:function(_7,_8,_9){var s=df.rawLambda(_7);if(_9){df.forEach(s.args,_9);}var ap=typeof _8=="string",n=ap?s.args.length:Math.min(s.args.length,_8.length),a=new Array(4*n+4),i,j=1;for(i=0;i<n;++i){a[j++]=s.args[i];a[j++]="=";a[j++]=ap?_8+"["+i+"]":_8[i];a[j++]=",";}a[0]="(";a[j++]="(";a[j++]=s.body;a[j]="))";return a.join("");}});})();}}};});