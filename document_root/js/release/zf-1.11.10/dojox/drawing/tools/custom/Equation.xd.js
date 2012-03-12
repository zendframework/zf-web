/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dojox.drawing.tools.custom.Equation"],["require","dojox.drawing.tools.TextBlock"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dojox.drawing.tools.custom.Equation"]){_4._hasResource["dojox.drawing.tools.custom.Equation"]=true;_4.provide("dojox.drawing.tools.custom.Equation");_4.require("dojox.drawing.tools.TextBlock");_6.drawing.tools.custom.Equation=_6.drawing.util.oo.declare(_6.drawing.tools.TextBlock,function(_7){},{customType:"equation"});_6.drawing.tools.custom.Equation.setup={name:"dojox.drawing.tools.custom.Equation",tooltip:"Equation Tool",iconClass:"iconEq"};_6.drawing.register(_6.drawing.tools.custom.Equation.setup,"tool");}}};});