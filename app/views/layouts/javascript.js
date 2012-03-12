var zend = { }; 
dojo.addOnLoad(function(){
    dojo.query("#top-nav > li").forEach(function(n){
        dojo.connect(n,"onmouseenter", dojo.hitch(dojo, "addClass", n, "over"));
        dojo.connect(n,"onmouseleave", dojo.hitch(dojo, "removeClass", n, "over"));
    });
});

