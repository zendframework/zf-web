dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "dijit.form.nls.validate"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("dijit.form.nls.validate");dojo._xdLoadFlattenedBundle("dijit.form", "validate", "", {"rangeMessage":"This value is out of range.","invalidMessage":"The value entered is not valid.","missingMessage":"This value is required."});
}};});