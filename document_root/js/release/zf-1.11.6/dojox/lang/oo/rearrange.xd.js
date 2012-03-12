/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.lang.oo.rearrange"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.lang.oo.rearrange"]){_4._hasResource["dojox.lang.oo.rearrange"]=true;_4.provide("dojox.lang.oo.rearrange");(function(){var _7=_4._extraNames,_8=_7.length,_9=Object.prototype.toString;_6.lang.oo.rearrange=function(_a,_b){var _c,_d,_e,i,t;for(_c in _b){_d=_b[_c];if(!_d||_9.call(_d)=="[object String]"){_e=_a[_c];if(!(_c in empty)||empty[_c]!==_e){if(!(delete _a[_c])){_a[_c]=undefined;}if(_d){_a[_d]=_e;}}}}if(_8){for(i=0;i<_8;++i){_c=_7[i];_d=_b[_c];if(!_d||_9.call(_d)=="[object String]"){_e=_a[_c];if(!(_c in empty)||empty[_c]!==_e){if(!(delete _a[_c])){_a[_c]=undefined;}if(_d){_a[_d]=_e;}}}}}return _a;};})();}}};});