/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {depends:[["provide","dijit.form.VerticalRuleLabels"],["require","dijit.form.HorizontalRuleLabels"]],defineResource:function(_4,_5,_6){if(!_4._hasResource["dijit.form.VerticalRuleLabels"]){_4._hasResource["dijit.form.VerticalRuleLabels"]=true;_4.provide("dijit.form.VerticalRuleLabels");_4.require("dijit.form.HorizontalRuleLabels");_4.declare("dijit.form.VerticalRuleLabels",_5.form.HorizontalRuleLabels,{templateString:"<div class=\"dijitRuleContainer dijitRuleContainerV dijitRuleLabelsContainer dijitRuleLabelsContainerV\"></div>",_positionPrefix:"<div class=\"dijitRuleLabelContainer dijitRuleLabelContainerV\" style=\"top:",_labelPrefix:"\"><span class=\"dijitRuleLabel dijitRuleLabelV\">",_calcPosition:function(_7){return 100-_7;}});}}};});