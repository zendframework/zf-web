/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojo.data.util.simpleFetch"],["require","dojo.data.util.sorter"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojo.data.util.simpleFetch"]){_4._hasResource["dojo.data.util.simpleFetch"]=true;_4.provide("dojo.data.util.simpleFetch");_4.require("dojo.data.util.sorter");_4.data.util.simpleFetch.fetch=function(_7){_7=_7||{};if(!_7.store){_7.store=this;}var _8=this;var _9=function(_a,_b){if(_b.onError){var _c=_b.scope||_4.global;_b.onError.call(_c,_a,_b);}};var _d=function(_e,_f){var _10=_f.abort||null;var _11=false;var _12=_f.start?_f.start:0;var _13=(_f.count&&(_f.count!==Infinity))?(_12+_f.count):_e.length;_f.abort=function(){_11=true;if(_10){_10.call(_f);}};var _14=_f.scope||_4.global;if(!_f.store){_f.store=_8;}if(_f.onBegin){_f.onBegin.call(_14,_e.length,_f);}if(_f.sort){_e.sort(_4.data.util.sorter.createSortFunction(_f.sort,_8));}if(_f.onItem){for(var i=_12;(i<_e.length)&&(i<_13);++i){var _15=_e[i];if(!_11){_f.onItem.call(_14,_15,_f);}}}if(_f.onComplete&&!_11){var _16=null;if(!_f.onItem){_16=_e.slice(_12,_13);}_f.onComplete.call(_14,_16,_f);}};this._fetchItems(_7,_d,_9);return _7;};}}};});