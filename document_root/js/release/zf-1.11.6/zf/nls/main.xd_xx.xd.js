dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_xx"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.xx"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.xx"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_xx");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.xx");dijit.nls.loading.xx={"loadingState":"Loading...","errorState":"Sorry, an error occurred"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.xx");dijit.nls.common.xx={"buttonOk":"OK","buttonCancel":"Cancel","buttonSave":"Save","itemClose":"Close"};

}};});