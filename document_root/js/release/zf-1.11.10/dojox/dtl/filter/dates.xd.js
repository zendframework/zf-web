/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.filter.dates"],["require","dojox.dtl.utils.date"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.filter.dates"]){_4._hasResource["dojox.dtl.filter.dates"]=true;_4.provide("dojox.dtl.filter.dates");_4.require("dojox.dtl.utils.date");(function(){var _7=_6.dtl.filter.dates;_4.mixin(_7,{_toDate:function(_8){if(_8 instanceof Date){return _8;}_8=new Date(_8);if(_8.getTime()==new Date(0).getTime()){return "";}return _8;},date:function(_9,_a){_9=_7._toDate(_9);if(!_9){return "";}_a=_a||"N j, Y";return _6.dtl.utils.date.format(_9,_a);},time:function(_b,_c){_b=_7._toDate(_b);if(!_b){return "";}_c=_c||"P";return _6.dtl.utils.date.format(_b,_c);},timesince:function(_d,_e){_d=_7._toDate(_d);if(!_d){return "";}var _f=_6.dtl.utils.date.timesince;if(_e){return _f(_e,_d);}return _f(_d);},timeuntil:function(_10,arg){_10=_7._toDate(_10);if(!_10){return "";}var _11=_6.dtl.utils.date.timesince;if(arg){return _11(arg,_10);}return _11(new Date(),_10);}});})();}}};});