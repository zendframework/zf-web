dojo.provide("zf.ManualNavigation");

(function(){
var d = dojo;

d.mixin(zf, {
    ManualNavigation: { generate: function() {
        // Generates a unique id for a node
        var id_count = 0;
        var _uniqueId = function(){
            var id_base = "dojo_blocked",
                id;
            do {
                id = id_base + "_" + (++id_count);
            } while (dojo.byId(id));
            return id;
        };

        var toc = function(section, container, margin) {
            if (typeof(section) === "undefined" || section === null || !section) {
                return;
            }
            if (!dojo.hasAttr(section, "id")) {
                dojo.attr(section, "id", _uniqueId());
            }

            var ul = dojo.create("ul");

            var getSectionTitles = function(child) {
                if (typeof(child) === "undefined" || child === null || !child) {
                    return;
                }
                if (!dojo.hasAttr(child, "id")) {
                    dojo.attr(child, "id", _uniqueId());
                }

                var li    = dojo.create("li");
                var link  = dojo.create("a", { href: "#" + dojo.attr(child, "id") });
                var titles = dojo.query("#" + dojo.attr(child, "id").replace(/(:|\.)/g,'\\$1') + " h1.title:eq(0)");
                if (!titles.length) {
                    return;
                }
                var title = titles.shift();
                dojo.attr(link, "innerHTML", dojo.attr(title, "innerHTML"));
                dojo.place(link, li, "last");
                dojo.place(li, ul, "last");

                toc(child, li, "0 0 0 15px");
            };

            var addSectionAnchorLink = function(child) {
                if (typeof(child) === "undefined" || child === null || !child) {
                    return;
                }
                if (!dojo.hasAttr(child, "name")) {
                    return;
                }
                var titles = dojo.query("#" + dojo.attr(child, "id").replace(/(:|\.)/g,'\\$1') + " h1.title:eq(0)");
                if (!titles.length) {
                    return;
                }
                var title = titles.shift();
                title.innerHTML += ' <a href="#' + dojo.attr(child, "name") + '" class="title-link-anchor">¶</a>';
            };

            var processSection = function(child) {
                getSectionTitles(child);
                addSectionAnchorLink(child);
            };

            dojo.query("#" + dojo.attr(section, "id").replace(/(:|\.)/g,'\\$1') + ' > .section[id^="zend."]').forEach(processSection);
            dojo.query("#" + dojo.attr(section, "id").replace(/(:|\.)/g,'\\$1') + ' > .section[id^="learning."]').forEach(processSection);

            if (dojo.query("li", ul).length) {
                dojo.style(ul, {
                    margin: margin,
                    padding: 0
                });
                dojo.place(ul, container, "last");
            }
        };

        var manual    = dojo.byId("manual-container");
        var container = dojo.create("div", { "class": "block" });
        dojo.create("h2", { "class": "navigation", innerHTML: "Page Navigation" }, container, "last");
        var block     = dojo.create("div", { "class": "block-in" }, container, "last");

        toc(manual, block, 0);
        if (dojo.query("ul li", block).length) {
            dojo.place(container, dojo.query("div.right-nav").shift(), "last");
        }
    }}
});
})();
