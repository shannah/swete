//require <xataface/modules/testrunner/TestRunner.js>
//require <xataface/modules/tm/string_parser.js>
(function(){
	var $ = jQuery;
	var TestRunner = xataface.modules.testrunner.TestRunner;
	var StringForm = xataface.modules.tm.StringForm;
	
	TestRunner.addTest(test_parser);
	
	function test_parser(){
		
		var testRoot = 'xataface.modules.tm.tests.test_string_parser: ';
	
		var strs = {
			'<span id="2">*</span> First name' : '<g id="1">*</g> First name',
			'Hello <span class="foo">Steve</span>' : 'Hello <g id="1">Steve</g>',
			'<span class="hello">Hello</span> <span class="steve">Steve</span>':'<g id="1">Hello</g> <g id="2">Steve</g>',
			'<a href="#"><span class="hello">Hello</span> World</a>':'<g id="1"><g id="2">Hello</g> World</g>',
			'John was here: <img src="foo"/> Now':'John was here: <x id="1"/> Now',
			'John < Bill' : 'John < Bill',
			'John <Bill' : 'John <Bill',
			'John > Bill' : 'John > Bill',
			'John < <span>Bill</span>' : 'John &lt; <g id="1">Bill</g>',
			'John < <span data-swete-translate="1">Bill</span>' : 'John &lt; <v id="1">Bill</v>',
			'John &lt; <span data-swete-translate="1">Bill</span>' : 'John &lt; <v id="1">Bill</v>'
		};
		var i =0;
		$.each(strs, function(key,val){
			TestRunner.assertEquals(testRoot+'Parse string '+(i++), val, StringForm.stripHtml(key, {}));
		});
	
	}

})();