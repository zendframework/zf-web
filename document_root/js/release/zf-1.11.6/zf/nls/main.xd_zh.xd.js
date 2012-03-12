dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_zh"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.zh"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.zh"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_zh");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.zh");dijit.nls.loading.zh={"loadingState":"正在加载...","errorState":"对不起，发生了错误"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.zh");dijit.nls.common.zh={"buttonOk":"确定","buttonCancel":"取消","buttonSave":"保存","itemClose":"关闭"};

}};});