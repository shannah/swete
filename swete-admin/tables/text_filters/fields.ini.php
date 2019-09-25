;<?php exit;
[filter_title]
	widget:description="A name for this filter.  For your reference only."
	
[pattern]
	widget:description="A regular expression to match. E.g. /Hello World/.   You must include the delimiters (i.e. leading and trailing slash).  <a target='_blank' href='http://en.wikipedia.org/wiki/Regular_expression'>More about Regular Expressions (Wikipedia)</a>."
	validators:regex="/^\/.*\/[a-z]*$/"
	validators:regex:message="Please enter a valid regular expression."
	widget:type=textarea
	
[replacement]
	widget:description="Enter the text that should replace pattern matches.  You can embed the original matching string using $0.  Date formatting should use the <a target='_blank' href='http://userguide.icu-project.org/formatparse/datetime'>ICU library date formatting conventions</a>."
	
[comments]
	widget:description="Enter comments to help explain what this filter does and how it should be used."
	
[default_order]
	widget:description="The default order for this filter.  If multiple filters are set to be applied for a site, they will be applied in the order prescribed by this order.  Should be an integer.  Default value is 0."
	
[is_default_prefilter]
	widget:type=checkbox
	widget:description="Should this filter be added as a pre-filter to new sites automatically?"
	
[is_default_postfilter]
	widget:type=checkbox
	widget:description="Should this filter be added as a post-filter to new sites automatically?"
	
[language]
	vocabulary=languages
	widget:type=select
	widget:description="If this filter should only apply to sites of a particular source language, select the language here."