dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_el"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.el"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.el"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_el");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.el");dijit.nls.loading.el={"loadingState":"Φόρτωση...","errorState":"Σας ζητούμε συγνώμη, παρουσιάστηκε σφάλμα"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.el");dijit.nls.common.el={"buttonOk":"ΟΚ","buttonCancel":"Ακύρωση","buttonSave":"Αποθήκευση","itemClose":"Κλείσιμο"};

}};});