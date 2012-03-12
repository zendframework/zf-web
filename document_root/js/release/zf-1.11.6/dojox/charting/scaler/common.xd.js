/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.charting.scaler.common"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.charting.scaler.common"]){_4._hasResource["dojox.charting.scaler.common"]=true;_4.provide("dojox.charting.scaler.common");(function(){var eq=function(a,b){return Math.abs(a-b)<=0.000001*(Math.abs(a)+Math.abs(b));};_4.mixin(_6.charting.scaler.common,{findString:function(_7,_8){_7=_7.toLowerCase();for(var i=0;i<_8.length;++i){if(_7==_8[i]){return true;}}return false;},getNumericLabel:function(_9,_a,_b){var _c=_b.fixed?_9.toFixed(_a<0?-_a:0):_9.toString();if(_b.labelFunc){var r=_b.labelFunc(_c,_9,_a);if(r){return r;}}if(_b.labels){var l=_b.labels,lo=0,hi=l.length;while(lo<hi){var _d=Math.floor((lo+hi)/2),_e=l[_d].value;if(_e<_9){lo=_d+1;}else{hi=_d;}}if(lo<l.length&&eq(l[lo].value,_9)){return l[lo].text;}--lo;if(lo>=0&&lo<l.length&&eq(l[lo].value,_9)){return l[lo].text;}lo+=2;if(lo<l.length&&eq(l[lo].value,_9)){return l[lo].text;}}return _c;}});})();}}};});