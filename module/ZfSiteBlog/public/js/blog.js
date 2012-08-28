define(["dojo/query", "dojox/highlight", "dojox/highlight/languages/_all", "dojox/highlight/languages/pygments/css", "dojo/domReady!"], function(query, highlight) {
    query("div.example pre code").forEach(highlight.init);
});
