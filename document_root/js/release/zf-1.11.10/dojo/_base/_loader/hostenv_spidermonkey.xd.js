/*
	Copyright (c) 2004-2009, The Dojo Foundation All Rights Reserved.
	Available via Academic Free License >= 2.1 OR the modified BSD license.
	see: http://dojotoolkit.org/license for details
*/


dojo._xdResourceLoaded(function(_1,_2,_3){return {defineResource:function(_4,_5,_6){if(_4.config["baseUrl"]){_4.baseUrl=_4.config["baseUrl"];}else{_4.baseUrl="./";}_4._name="spidermonkey";_4.isSpidermonkey=true;_4.exit=function(_7){quit(_7);};if(typeof print=="function"){console.debug=print;}if(typeof line2pc=="undefined"){throw new Error("attempt to use SpiderMonkey host environment when no 'line2pc' global");}_4._spidermonkeyCurrentFile=function(_8){var s="";try{throw Error("whatever");}catch(e){s=e.stack;}var _9=s.match(/[^@]*\.js/gi);if(!_9){throw Error("could not parse stack string: '"+s+"'");}var _a=(typeof _8!="undefined"&&_8)?_9[_8+1]:_9[_9.length-1];if(!_a){throw Error("could not find file name in stack string '"+s+"'");}return _a;};_4._loadUri=function(_b){var ok=load(_b);return 1;};if(_4.config["modulePaths"]){for(var _c in _4.config["modulePaths"]){_4.registerModulePath(_c,_4.config["modulePaths"][_c]);}}}};});