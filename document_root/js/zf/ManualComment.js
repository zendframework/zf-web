dojo.provide("zf.ManualComment");

(function(){
var d = dojo;

d.mixin(zf, {
    commentHandler: function(e) {
        e.preventDefault();
        var form = e.target;
        d.query(".comment-form").block();
        d.xhrPost({
            url:  form.action,
            handleAs: "json",
            form: form,
            load: function(data, ioArgs){
                if (!data.success) {
                    d.query(".comment-form form").replaceWith(data.content);
                    d.query(".comment-form form").onsubmit(zf.commentHandler);
                } else {
                    var comments = d.query(".comments");
                    if (comments.length > 0) {
                        comments.replaceWith(data.content);
                    } else {
                        var c = data.content;
                        d.query(".comment-form").at(0).forEach(function(n){
                            place(c, n, "before");
                        });
                    }
                    form.reset();
                }
                d.query(".comment-form").unblock();

                var dialog = new dijit.Dialog({
                    title: "Comment Added",
                    content: "Your comment has been posted to our system. If you do not see it immediately, it may have been flagged for moderation; check back later to see if it has been accepted.",
                    style: "width: 300px;"
                });
                dialog.show();

                return data;
            },
            error: function(response, ioArgs){
                var dialog = new dijit.Dialog({
                    title: "An error occurred",
                    content: "<p>An error occurred with your comment submission; please try again later.</p>",
                    style: "width: 300px;"
                });
                dialog.show();
                d.query(".comment-form").unblock();
                return response;
            }
        });
    }
});
})();
