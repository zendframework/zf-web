/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.validate.isbn"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.validate.isbn"]){_4._hasResource["dojox.validate.isbn"]=true;_4.provide("dojox.validate.isbn");_6.validate.isValidIsbn=function(_7){var _8,_9=0,_a;if(!_4.isString(_7)){_7=String(_7);}_7=_7.replace(/[- ]/g,"");_8=_7.length;switch(_8){case 10:_a=_8;for(var i=0;i<9;i++){_9+=parseInt(_7.charAt(i))*_a;_a--;}var t=_7.charAt(9).toUpperCase();_9+=t=="X"?10:parseInt(t);return _9%11==0;break;case 13:_a=-1;for(var i=0;i<_8;i++){_9+=parseInt(_7.charAt(i))*(2+_a);_a*=-1;}return _9%10==0;break;}return false;};}}};});