dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_pt"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.pt"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.pt"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_pt");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.pt");dijit.nls.loading.pt={"loadingState":"Carregando...","errorState":"Desculpe, ocorreu um erro"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.pt");dijit.nls.common.pt={"buttonOk":"OK","buttonCancel":"Cancelar","buttonSave":"Salvar","itemClose":"Fechar"};

}};});