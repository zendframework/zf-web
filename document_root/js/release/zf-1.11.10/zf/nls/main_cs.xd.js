dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_cs"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.cs"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.cs"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_cs");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.cs");dijit.nls.loading.cs={"loadingState":"Probíhá načítání...","errorState":"Omlouváme se, došlo k chybě"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.cs");dijit.nls.common.cs={"buttonOk":"OK","buttonCancel":"Storno","buttonSave":"Uložit","itemClose":"Zavřít"};

}};});