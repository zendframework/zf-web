dojo.provide("zf.download");

(function(){
zf.download.imagePath = "/images/download/";

zf.download.images = {
    "zs-download-button": {
        "toggled":   zf.download.imagePath+"/download_button_over.png",
        "untoggled": zf.download.imagePath+"/download_button.png"
    },
    "readme-arrow": {
        "toggled":   zf.download.imagePath+"/readme_arrow_down_over.gif",
        "untoggled": zf.download.imagePath+"/readme_arrow_down.gif"
    },
    "readme-arrow-dropdown": {
        "toggled":   zf.download.imagePath+"/readme_arrow_up_over.gif",
        "untoggled": zf.download.imagePath+"/readme_arrow_up.gif"
    }
};

zf.download.toggleButton = function(node) {
    if (dojo.hasClass(node, "toggled")) {
        node.src = zf.download.images[node.id].untoggled;
    } else {
        node.src = zf.download.images[node.id].toggled;
    }
    dojo.toggleClass(node, "toggled");
};

zf.download.toggleArrow = function(node) {
    var id = node.id + (dojo.hasClass(node, "dropdown") ? "-dropdown" : "");
    if (dojo.hasClass(node, "toggled")) {
        node.src = zf.download.images[id].untoggled;
    } else {
        node.src = zf.download.images[id].toggled;
    }
    dojo.toggleClass(node, "toggled");
};

dojo.addOnLoad(function(){

/*mouseover , mouseout, and onclick events for the read more button*/
dojo.query("#readme-arrow").onmouseover(function(e) {
    var img = e.target;
    zf.download.toggleArrow(img);
}).onmouseout(function(e) {
    var img = e.target;
    zf.download.toggleArrow(img);
}).onclick(function(e) {
    var img = e.target;
    dojo.toggleClass(img, "dropdown");
    zf.download.toggleArrow(img);
    dojo.query(".readme.content").toggleClass("show");
});
 
/*mouseover and mouseout for the download button*/
dojo.query("#zs-download-button").attr("onmouseover", function(e){
    var button = e.target;
    zf.download.toggleButton(button);
}).attr("onmouseout", function(e){
    var button = e.target;
    zf.download.toggleButton(button);
});

});
})();
