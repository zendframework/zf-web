/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.filter.logic"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.filter.logic"]){_4._hasResource["dojox.dtl.filter.logic"]=true;_4.provide("dojox.dtl.filter.logic");_4.mixin(_6.dtl.filter.logic,{default_:function(_7,_8){return _7||_8||"";},default_if_none:function(_9,_a){return (_9===null)?_a||"":_9||"";},divisibleby:function(_b,_c){return (parseInt(_b,10)%parseInt(_c,10))===0;},_yesno:/\s*,\s*/g,yesno:function(_d,_e){if(!_e){_e="yes,no,maybe";}var _f=_e.split(_6.dtl.filter.logic._yesno);if(_f.length<2){return _d;}if(_d){return _f[0];}if((!_d&&_d!==null)||_f.length<3){return _f[1];}return _f[2];}});}}};});