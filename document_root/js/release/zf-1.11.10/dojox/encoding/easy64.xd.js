/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.encoding.easy64"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.encoding.easy64"]){_4._hasResource["dojox.encoding.easy64"]=true;_4.provide("dojox.encoding.easy64");(function(){var c=function(_7,_8,_9){for(var i=0;i<_8;i+=3){_9.push(String.fromCharCode((_7[i]>>>2)+33),String.fromCharCode(((_7[i]&3)<<4)+(_7[i+1]>>>4)+33),String.fromCharCode(((_7[i+1]&15)<<2)+(_7[i+2]>>>6)+33),String.fromCharCode((_7[i+2]&63)+33));}};_6.encoding.easy64.encode=function(_a){var _b=[],_c=_a.length%3,_d=_a.length-_c;c(_a,_d,_b);if(_c){var t=_a.slice(_d);while(t.length<3){t.push(0);}c(t,3,_b);for(var i=3;i>_c;_b.pop(),--i){}}return _b.join("");};_6.encoding.easy64.decode=function(_e){var n=_e.length,r=[],b=[0,0,0,0],i,j,d;for(i=0;i<n;i+=4){for(j=0;j<4;++j){b[j]=_e.charCodeAt(i+j)-33;}d=n-i;for(j=d;j<4;b[++j]=0){}r.push((b[0]<<2)+(b[1]>>>4),((b[1]&15)<<4)+(b[2]>>>2),((b[2]&3)<<6)+b[3]);for(j=d;j<4;++j,r.pop()){}}return r;};})();}}};});