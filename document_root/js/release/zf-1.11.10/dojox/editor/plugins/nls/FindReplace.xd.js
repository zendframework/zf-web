dojo._xdResourceLoaded(function(dojo, dijit, dojox){
return {depends: [["provide", "dojox.editor.plugins.nls.FindReplace"]],
defineResource: function(dojo, dijit, dojox){dojo.provide("dojox.editor.plugins.nls.FindReplace");dojo._xdLoadFlattenedBundle("dojox.editor.plugins", "FindReplace", "", ({"backwards":"Backwards","findReplace":"Toggle Find/Replace","replaceAll":"All Occurances","replaceDialogText":"Replaced ${0} occurances.","replaceButton":"Replace","findButton":"Find","findLabel":"Find what:","matchCase":"Match Case","replaceLabel":"Replace with:"})
);
}};});