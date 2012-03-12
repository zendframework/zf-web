dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_it"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.it"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.it"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_it");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.it");dijit.nls.loading.it={"loadingState":"Caricamento in corso...","errorState":"Si è verificato un errore"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.it");dijit.nls.common.it={"buttonOk":"OK","buttonCancel":"Annulla","buttonSave":"Salva","itemClose":"Chiudi"};

}};});