/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.encoding.ascii85"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.encoding.ascii85"]){_4._hasResource["dojox.encoding.ascii85"]=true;_4.provide("dojox.encoding.ascii85");(function(){var c=function(_7,_8,_9){var i,j,n,b=[0,0,0,0,0];for(i=0;i<_8;i+=4){n=((_7[i]*256+_7[i+1])*256+_7[i+2])*256+_7[i+3];if(!n){_9.push("z");}else{for(j=0;j<5;b[j++]=n%85+33,n=Math.floor(n/85)){}}_9.push(String.fromCharCode(b[4],b[3],b[2],b[1],b[0]));}};_6.encoding.ascii85.encode=function(_a){var _b=[],_c=_a.length%4,_d=_a.length-_c;c(_a,_d,_b);if(_c){var t=_a.slice(_d);while(t.length<4){t.push(0);}c(t,4,_b);var x=_b.pop();if(x=="z"){x="!!!!!";}_b.push(x.substr(0,_c+1));}return _b.join("");};_6.encoding.ascii85.decode=function(_e){var n=_e.length,r=[],b=[0,0,0,0,0],i,j,t,x,y,d;for(i=0;i<n;++i){if(_e.charAt(i)=="z"){r.push(0,0,0,0);continue;}for(j=0;j<5;++j){b[j]=_e.charCodeAt(i+j)-33;}d=n-i;if(d<5){for(j=d;j<4;b[++j]=0){}b[d]=85;}t=(((b[0]*85+b[1])*85+b[2])*85+b[3])*85+b[4];x=t&255;t>>>=8;y=t&255;t>>>=8;r.push(t>>>8,t&255,y,x);for(j=d;j<5;++j,r.pop()){}i+=4;}return r;};})();}}};});