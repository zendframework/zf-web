/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.dtl.filter.integers"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.dtl.filter.integers"]){_4._hasResource["dojox.dtl.filter.integers"]=true;_4.provide("dojox.dtl.filter.integers");_4.mixin(_6.dtl.filter.integers,{add:function(_7,_8){_7=parseInt(_7,10);_8=parseInt(_8,10);return isNaN(_8)?_7:_7+_8;},get_digit:function(_9,_a){_9=parseInt(_9,10);_a=parseInt(_a,10)-1;if(_a>=0){_9+="";if(_a<_9.length){_9=parseInt(_9.charAt(_a),10);}else{_9=0;}}return (isNaN(_9)?0:_9);}});}}};});