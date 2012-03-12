/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {defineResource:function(_4,_5,_6){_6.embed.Flash.place=function(_7,_8){var o=_6.embed.Flash.__ie_markup__(_7);_8=_4.byId(_8);if(!_8){_8=_4.doc.createElement("div");_8.id=o.id+"-container";_4.body().appendChild(_8);}if(o){_8.innerHTML=o.markup;return o.id;}return null;};_6.embed.Flash.onInitialize();}};});