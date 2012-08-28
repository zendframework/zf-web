define(["dojo/query", "dojo/dom-construct", "dojox/highlight", "dojo/NodeList-manipulate", "dojox/highlight/languages/_all", "dojox/highlight/languages/pygments/css", "dojo/domReady!"], function(query, domConstruct, highlight) {
    query("pre.highlight").wrapInner('<code></code>').wrapAll('<div class="example"></div>');
    query("pre.highlight code").forEach(highlight.init);
});
