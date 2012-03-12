/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.math.random.Secure"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.math.random.Secure"]){_4._hasResource["dojox.math.random.Secure"]=true;_4.provide("dojox.math.random.Secure");_4.declare("dojox.math.random.Secure",null,{constructor:function(_7,_8){this.prng=_7;var p=this.pool=new Array(_7.size);this.pptr=0;for(var i=0,_9=_7.size;i<_9;){var t=Math.floor(65536*Math.random());p[i++]=t>>>8;p[i++]=t&255;}this.seedTime();if(!_8){this.h=[_4.connect(_4.body(),"onclick",this,"seedTime"),_4.connect(_4.body(),"onkeypress",this,"seedTime")];}},destroy:function(){if(this.h){_4.forEach(this.h,_4.disconnect);}},nextBytes:function(_a){var _b=this.state;if(!_b){this.seedTime();_b=this.state=this.prng();_b.init(this.pool);for(var p=this.pool,i=0,_c=p.length;i<_c;p[i++]=0){}this.pptr=0;}for(var i=0,_c=_a.length;i<_c;++i){_a[i]=_b.next();}},seedTime:function(){this._seed_int(new Date().getTime());},_seed_int:function(x){var p=this.pool,i=this.pptr;p[i++]^=x&255;p[i++]^=(x>>8)&255;p[i++]^=(x>>16)&255;p[i++]^=(x>>24)&255;if(i>=this.prng.size){i-=this.prng.size;}this.pptr=i;}});}}};});