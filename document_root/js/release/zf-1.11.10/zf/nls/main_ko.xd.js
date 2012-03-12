dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_ko"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.ko"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.ko"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_ko");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.ko");dijit.nls.loading.ko={"loadingState":"로드 중...","errorState":"죄송합니다. 오류가 발생했습니다."};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.ko");dijit.nls.common.ko={"buttonOk":"확인","buttonCancel":"취소","buttonSave":"저장","itemClose":"닫기"};

}};});