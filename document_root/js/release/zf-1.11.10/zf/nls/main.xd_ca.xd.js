dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_ca"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.ca"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.ca"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_ca");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.ca");dijit.nls.loading.ca={"loadingState":"S'està carregant...","errorState":"Ens sap greu. S'ha produït un error."};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.ca");dijit.nls.common.ca={"buttonOk":"D'acord","buttonCancel":"Cancel·la","buttonSave":"Desa","itemClose":"Tanca"};

}};});