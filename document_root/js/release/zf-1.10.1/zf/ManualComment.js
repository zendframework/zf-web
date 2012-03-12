/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


if(!dojo._hasResource["zf.ManualComment"]){dojo._hasResource["zf.ManualComment"]=true;dojo.provide("zf.ManualComment");(function(){var d=dojo;d.mixin(zf,{commentHandler:function(e){e.preventDefault();var _1=e.target;d.query(".comment-form").block();d.xhrPost({url:_1.action,handleAs:"json",form:_1,load:function(_2,_3){if(!_2.success){d.query(".comment-form form").replaceWith(_2.content);d.query(".comment-form form").onsubmit(zf.commentHandler);}else{var _4=d.query(".comments");if(_4.length>0){_4.replaceWith(_2.content);}else{var c=_2.content;d.query(".comment-form").at(0).forEach(function(n){place(c,n,"before");});}_1.reset();}d.query(".comment-form").unblock();var _5=new dijit.Dialog({title:"Comment Added",content:"Your comment has been posted to our system. If you do not see it immediately, it may have been flagged for moderation; check back later to see if it has been accepted.",style:"width: 300px;"});_5.show();return _2;},error:function(_6,_7){var _8=new dijit.Dialog({title:"An error occurred",content:"<p>An error occurred with your comment submission; please try again later.</p>",style:"width: 300px;"});_8.show();d.query(".comment-form").unblock();return _6;}});}});})();}