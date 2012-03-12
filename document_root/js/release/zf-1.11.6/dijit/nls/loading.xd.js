dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "dijit.nls.loading"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("dijit.nls.loading");dojo._xdLoadFlattenedBundle("dijit", "loading", "", {"loadingState":"Loading...","errorState":"Sorry, an error occurred"});
}};});