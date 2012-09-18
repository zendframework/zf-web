define(["dojo/query", "dojo/dom", "dojo/dom-attr", "dojo/dom-construct", "dojo/dom-style", "dojo/domReady!"], function(query, dom, domAttr, domConstruct, domStyle) {
    // Generates a unique id for a node
    var id_count = 0;
    var _uniqueId = function(){
        var id_base = "dojo_blocked",
            id;
        do {
            id_count += 1;
            id = id_base + "_" + id_count;
        } while (dom.byId(id));
        return id;
    };

    var toc = function(section, container, margin) {
        if (typeof(section) === "undefined" || section === null || !section) {
            return;
        }
        if (!domAttr.has(section, "id")) {
            domAttr.set(section, "id", _uniqueId());
        }

        var ul = domConstruct.create("ul", {"class": "manual toc"});

        var getSectionTitles = function(child) {
            if (typeof(child) === "undefined" || child === null || !child) {
                return;
            }
            if (!domAttr.has(child, "id")) {
                domAttr.set(child, "id", _uniqueId());
            }

            var li    = domConstruct.create("li");
            var link  = domConstruct.create("a", { href: "#" + domAttr.get(child, "id") });
            var titles = query("#" + domAttr.get(child, "id").replace(/(:|\.)/g,'\\$1') + " h1.title:eq(0)");
            if (!titles.length) {
                return;
            }
            var title = titles.shift();
            domAttr.set(link, "innerHTML", domAttr.get(title, "innerHTML"));
            domConstruct.place(link, li, "last");
            domConstruct.place(li, ul, "last");

            toc(child, li, "0 0 0 5px");
        };

        var addSectionAnchorLink = function(child) {
            if (typeof(child) === "undefined" || child === null || !child) {
                return;
            }
            if (!domAttr.has(child, "name")) {
                return;
            }
            var titles = query("#" + domAttr.get(child, "id").replace(/(:|\.)/g,'\\$1') + " h1.title:eq(0)");
            if (!titles.length) {
                return;
            }
            var title = titles.shift();
            title.innerHTML += ' <a href="#' + domAttr.get(child, "name") + '" class="title-link-anchor">¶</a>';
        };

        var processSection = function(child) {
            getSectionTitles(child);
            addSectionAnchorLink(child);
        };

        query("#" + domAttr.get(section, "id").replace(/(:|\.)/g,'\\$1') + ' > .section[id^="zend."]').forEach(processSection);
        query("#" + domAttr.get(section, "id").replace(/(:|\.)/g,'\\$1') + ' > .section[id^="learning."]').forEach(processSection);

        if (query("li", ul).length) {
            domStyle.set(ul, {
                margin: margin
            });
            domConstruct.place(ul, container, "last");
        }
    };

    var manual    = dom.byId("manual-container");
    var container = domConstruct.create("div");

    toc(manual, container, 0);
    if (query("ul li", container).length) {
        var overview = query("div.overview").shift();
        domConstruct.create("h2", { innerHTML: "Page Navigation" }, overview, "last");
        domConstruct.place(query("ul", container).shift(), overview, "last");
    }
});
