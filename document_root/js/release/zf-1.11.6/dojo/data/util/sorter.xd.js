/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.data.util.sorter"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.data.util.sorter"]){_4._hasResource["dojo.data.util.sorter"]=true;_4.provide("dojo.data.util.sorter");_4.data.util.sorter.basicComparator=function(a,b){var r=-1;if(a===null){a=undefined;}if(b===null){b=undefined;}if(a==b){r=0;}else{if(a>b||a==null){r=1;}}return r;};_4.data.util.sorter.createSortFunction=function(_7,_8){var _9=[];function _a(_b,_c,_d,s){return function(_e,_f){var a=s.getValue(_e,_b);var b=s.getValue(_f,_b);return _c*_d(a,b);};};var _10;var map=_8.comparatorMap;var bc=_4.data.util.sorter.basicComparator;for(var i=0;i<_7.length;i++){_10=_7[i];var _11=_10.attribute;if(_11){var dir=(_10.descending)?-1:1;var _12=bc;if(map){if(typeof _11!=="string"&&("toString" in _11)){_11=_11.toString();}_12=map[_11]||bc;}_9.push(_a(_11,dir,_12,_8));}}return function(_13,_14){var i=0;while(i<_9.length){var ret=_9[i++](_13,_14);if(ret!==0){return ret;}}return 0;};};}}};});