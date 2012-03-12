dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_pl"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.pl"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.pl"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_pl");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.pl");dijit.nls.loading.pl={"loadingState":"Ładowanie...","errorState":"Niestety, wystąpił błąd"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.pl");dijit.nls.common.pl={"buttonOk":"OK","buttonCancel":"Anuluj","buttonSave":"Zapisz","itemClose":"Zamknij"};

}};});