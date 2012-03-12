dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_ROOT"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.ROOT"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.ROOT"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_ROOT");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.ROOT");dijit.nls.loading.ROOT={"loadingState":"Loading...","errorState":"Sorry, an error occurred"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.ROOT");dijit.nls.common.ROOT={"buttonOk":"OK","buttonCancel":"Cancel","buttonSave":"Save","itemClose":"Close"};

}};});