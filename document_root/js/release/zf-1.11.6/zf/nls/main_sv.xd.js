dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_sv"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.sv"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.sv"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_sv");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.sv");dijit.nls.loading.sv={"loadingState":"Läser in...","errorState":"Det uppstod ett fel."};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.sv");dijit.nls.common.sv={"buttonOk":"OK","buttonCancel":"Avbryt","buttonSave":"Spara","itemClose":"Stäng"};

}};});