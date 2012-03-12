dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_nl"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.nl"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.nl"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_nl");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.nl");dijit.nls.loading.nl={"loadingState":"Bezig met laden...","errorState":"Er is een fout opgetreden"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.nl");dijit.nls.common.nl={"buttonOk":"OK","buttonCancel":"Annuleren","buttonSave":"Opslaan","itemClose":"Sluiten"};

}};});