dojo.provide("zf.Block");

/** 
 * Based on: 
 * http://www.sitepen.com/blog/2008/10/17/dojo-building-blocks-of-the-web/
 *
 * Usage:
 * zf.block(somenode);
 * zf.unblock(somenode);
 *
 * dojo.query("some nodes").block().unblock();
 */

(function(){
var d = dojo;
d.declare("zf._Blocker", null, {
    duration: 400, 
    opacity: 0.6,
    backgroundColor: "#fff",
    zIndex: 999,
    
    constructor: function(node, args){
        // mixin the passed properties into this instance
        d.mixin(this, args);
        this.node = d.byId(node);

        // create a node for our overlay.
        this.overlay = d.doc.createElement("div");

        // do some chained magic nonsense
        d.query(this.overlay)
                .place(d.body(),"last")
                .addClass("zfBlockOverlay")
                .style({
                    backgroundColor: this.backgroundColor,
                    position: "absolute",
                    zIndex: this.zIndex,
                    display: "none",
                    opacity: this.opacity
                });
    },
    
    show: function(){
        // summary: Show this overlay 
        var pos = d.coords(this.node, true),
            ov = this.overlay;
        d.marginBox(ov, pos);
        d.style(ov, { opacity:0, display:"block" });
        d.anim(ov, { opacity: this.opacity }, this.duration);
    },
    
    hide: function(){
        // summary: Hide this overlay
        d.fadeOut({
            node: this.overlay,
            duration: this.duration,
            // when fadeout is done, set the overlay to display: none
            onEnd: d.hitch(this, function(){
                d.style(this.overlay, "display", "none");
            })
        }).play();
    }
});

// cache of all blockers
var blockers = [];

d.mixin(zf, {
    block: function(node, args){
        var n = d.byId(node);
        var id = d.attr(n, "id");
        if (!id) {
            id = _uniqueId();
            d.attr(n, "id", id);
        }
        if (!blockers[id]) {
            blockers[id] = new zf._Blocker(n, args);
        }
        blockers[id].show();
        return blockers[id];
    },
    unblock: function(node, args){
        var id = d.attr(node, "id");
        if (id && blockers[id]) {
            blockers[id].hide();
        }
    }
});

var id_count = 0;
var _uniqueId = function(){
    var id_base = "zf_blocked",
        id;
    do{
        id = id_base + "_" + (++id_count);
    }while(d.byId(id));
    return id;
};

d.extend(d.NodeList, {  
    block: function(args){
        return this.forEach(function(n){
            zf.block(n, args);
        });
    },
    unblock: function(args){
        return this.forEach(function(n){
            zf.unblock(n, args);
        });
    }
});

})();
