;<?php exit;
default_action=dashboard

[fieldgroup:more]
	label=More Details
	collapsed=1

[source_language]
	widget:type=lookup
	widget:table=languages
	vocabulary=languages
	widget:description="Select the language of the source website. (i.e. the original language from which you are translating)"


[website_name]
	widget:description="Enter a name for this site.  This name is purely for your reference and will not affect how the site is published."

[website_url]
	widget:description="The URL of the source website. e.g http://example.com/"

[target_language]
	widget:type=lookup
	widget:table=languages
	vocabulary=languages
	widget:description="Select the language to publish for your proxy site.  (i.e. the language to which you are translating.)"


[host]
	widget:label="Publish Host"
	widget:description="The hostname (or subdomain) through which the translated version of this site should be accessble.  E.g. example.com"

[base_path]
	widget:label="Publish Basepath"
	widget:description="The basepath for the translated version of this site.  The full URL to access your translated site will be http://{publish host}{public base path}."

[translation_memory_id]
	widget:type=hidden
	widget:table=xf_tm_translation_memories

[active]
	widget:type=checkbox
	group=more

[locked]
	;widget:type=checkbox
	widget:type=hidden
	;; Keep this hidden for now. It only makes sense on static sites, and we're working
	;; toward finishing a product for dynamic sites first
	group=more

[auto_approve]
	group=more
	widget:type=hidden

[enable_live_translation]
	widget:description="Enable this option if this site is a dynamic site (e.g. shopping carts or any site that shows different content to different users)."
	widget:type=checkbox

[log_translation_misses]
	widget:type=checkbox
	group=more
	widget:description="Enable this option if you want to log any strings that are encountered that cannot be translated because they aren't part of the translation memory yet.
	This adds extra overhead and should not be enabled in production systems.
	"

[log_requests]
	widget:type=checkbox
	group=more
	widget:description="Enable this option to log all page requests.  This adds extra overhead.  For busier sites you should leave this option off and look at other options (e.g. Google Analytics) for logging requests."

[google_api_key]
	widget:description="If you want to be able to perform machine translations using the Google Translate API, enter your API key here.  Find out more about the <a target='blank' href='https://developers.google.com/translate/'>Google Translate API</a>."
	widget:label="Google API Key"
	group=more
	Default=1

[source_date_locale]
    widget:description="The locale that should be used for parsing dates in the source content.  This must be a locale that is installed on the system."
    group=more
    widget:type=yui_autocomplete
    vocabulary=system_locales

[target_date_locale]
    widget:description="The locale that should be used for formatting dates in the translated content.  This must be a locale that is installed on the system."
    group=more
    widget:type=yui_autocomplete
    vocabulary=system_locales

[webservice_secret_key]
    group=more
    widget:description="A secret key to be used for REST requests."

[translation_parser_version]
    group=more
    widget:description="DO NOT CHANGE unless you know what you are doing.  This is the version of the HTML translation parser that should be used for this site.  If you change this it may result in some translations not being picked up during the translation phase."
    widget:type=select
    vocabulary=translation_parser_versions

	[current_snapshot_id]
		widget:type=hidden
		visibility:list=hidden
