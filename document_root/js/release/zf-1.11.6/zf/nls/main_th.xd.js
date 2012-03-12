dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "zf.nls.main_th"],
["provide", "dijit.nls.loading"],
["provide", "dijit.nls.loading.th"],
["provide", "dijit.nls.common"],
["provide", "dijit.nls.common.th"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("zf.nls.main_th");dojo.provide("dijit.nls.loading");dijit.nls.loading._built=true;dojo.provide("dijit.nls.loading.th");dijit.nls.loading.th={"loadingState":"กำลังโหลด...","errorState":"ขออภัย เกิดข้อผิดพลาด"};dojo.provide("dijit.nls.common");dijit.nls.common._built=true;dojo.provide("dijit.nls.common.th");dijit.nls.common.th={"buttonOk":"ตกลง","buttonCancel":"ยกเลิก","buttonSave":"บันทึก","itemClose":"ปิด"};

}};});