//require <xataface/modules/tm/translation_memory.js>
(function(){
	var TranslationMemory = XataJax.load('xataface.modules.tm.TranslationMemory');
	
	(function(){
	
		$.extend(TranslationMemory.prototype, {
			flagStrings: flagStrings
		});
		
		
		/**
		 * @function 
		 * @description Flags or unflags one or more strings in the translation
		 * memory to mark them for retranslation.
		 *
		 * @memberOf xataface.modules.tm.TranslationMemory#
		 * 
		 * @param {Object} params
		 * @param {Array} params.strings Array of strings to flag.
		 * @param {Boolean} params.flagValue Whether to flag or unflag the strings.  Default is true.
		 * @param {Function} params.callback A callback function to handle the 
		 *  server response.
		 *
		 * @example
		 * tm.flagStrings({
		 *     strings: ['hello world', 'goodbye world'],
		 *     flagValue: true,
		 *     callback: function(res){
		 *         if ( res.code == 200 ){
		 *             alert('successfully flagged strings');
		 *         } else {
		 *             alert('failed to flag strings');
		 *         }
		 *     }
		 * });
		 */
		function flagStrings(params){
			var self = this;
			if ( typeof(params.strings) == 'undefined' ) throw "No strings provided in flagStrings";
			
			var strings = params.strings;
			
			var flagValue = true;
			if ( typeof(params.flagValue) != 'undefined' ) flagValue = params.flagValue;
			
			flagValue = flagValue ? '1':'0';
			
			var q = {
				'-action': 'tm_flag_translation',
				'--source': this.sourceLanguage,
				'--dest': this.destinationLanguage,
				'--strings[]': strings,
				'--flag_value': flagValue
			
			};
			
			if ( this.id ) q['--tmid'] = this.id;
			
			$.post(DATAFACE_SITE_HREF, q, function(res){
			
				if ( typeof(params.callback) == 'function' ){
					params.callback.call(self, res);
				}
				
			});
			
			
		}
		
	})();

})();