dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_sk"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.sk"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.sk"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_sk");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.sk");dijit.nls.loading.sk={"loadingState":"Zavádzanie...","errorState":"Nastala chyba"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.sk");dijit.nls.common.sk={"buttonOk":"OK","buttonCancel":"Zrušiť","buttonSave":"Uložiť","itemClose":"Zatvoriť"};

}};});