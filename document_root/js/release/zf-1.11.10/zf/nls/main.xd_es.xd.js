dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_es"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.es"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.es"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_es");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.es");dijit.nls.loading.es={"loadingState":"Cargando...","errorState":"Lo siento, se ha producido un error"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.es");dijit.nls.common.es={"buttonOk":"Aceptar","buttonCancel":"Cancelar","buttonSave":"Guardar","itemClose":"Cerrar"};

}};});