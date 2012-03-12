/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.manager.Undo"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.manager.Undo"]){_4._hasResource["dojox.drawing.manager.Undo"]=true;_4.provide("dojox.drawing.manager.Undo");_6.drawing.manager.Undo=_6.drawing.util.oo.declare(function(_7){this.keys=_7.keys;this.undostack=[];this.redostack=[];_4.connect(this.keys,"onKeyDown",this,"onKeyDown");},{onKeyDown:function(_8){if(!_8.cmmd){return;}if(_8.keyCode==90&&!_8.shift){this.undo();}else{if((_8.keyCode==90&&_8.shift)||_8.keyCode==89){this.redo();}}},add:function(_9){_9.args=_4.mixin({},_9.args);this.undostack.push(_9);},apply:function(_a,_b,_c){_4.hitch(_a,_b)(_c);},undo:function(){var o=this.undostack.pop();console.log("undo!",o);if(!o){return;}o.before();this.redostack.push(o);},redo:function(){console.log("redo!");var o=this.redostack.pop();if(!o){return;}if(o.after){o.after();}else{o.before();}this.undostack.push(o);}});}}};});