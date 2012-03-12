/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.math.random.prng4"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.math.random.prng4"]){_4._hasResource["dojox.math.random.prng4"]=true;_4.provide("dojox.math.random.prng4");(function(){function _7(){this.i=0;this.j=0;this.S=new Array(256);};_4.extend(_7,{init:function(_8){var i,j,t,S=this.S,_9=_8.length;for(i=0;i<256;++i){S[i]=i;}j=0;for(i=0;i<256;++i){j=(j+S[i]+_8[i%_9])&255;t=S[i];S[i]=S[j];S[j]=t;}this.i=0;this.j=0;},next:function(){var t,i,j,S=this.S;this.i=i=(this.i+1)&255;this.j=j=(this.j+S[i])&255;t=S[i];S[i]=S[j];S[j]=t;return S[(t+S[i])&255];}});_6.math.random.prng4=function(){return new _7();};_6.math.random.prng4.size=256;})();}}};});