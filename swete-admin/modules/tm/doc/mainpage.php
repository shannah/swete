<?php
/**

@mainpage Xataface Translation Memory Module

@section Synopsis

The Xataface Translation Memory Module provides a revamped translation form Xataface applications with multilingual content enabled.  This translation form includes a string parser and integrated translation memory so that fields containing large amounts of HTML content can be broken up into smaller parts and compared against the growing memory of previous translations.


<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-10-04_at_4.59.22_PM.png?max_width=640"/>

@section features Features

-# Fresh-new translation form
-# Separate translation memory for each language pair
-# Automatic translation memory matching on translation form.
-# Automatic saving to translation memory.
-# Does not conflict with existing database translations (translation memory is only applied to the translation form).
-# Parses content into individual strings for easy line-by-line translation.


@section Requirements

-# Xataface 2.0
-# Multilingual Content Turned on

@section Downloads

No releases yet.

SVN Trunk: <a href="http://weblite.ca/svn/dataface/modules/tm/trunk">http://weblite.ca/svn/dataface/modules/tm/trunk</a>


@section Installation

-# Download the latest sources.
-# Place the sources in either your application's modules directory or the Xataface modules directory.  The path should be one of:
	-# %APP_PATH%/modules/tm
	-# %XATAFACE_PATH%/modules/tm
-# Enable the module in your application's @ref http://xataface.com/wiki/conf.ini_file "conf.ini file" by adding the following to the @code [_modules] @endcode section: @code
[_modules]
	... other definitions ...
	modules_tm=modules/tm/tm.php
@endcode
-# Make sure that your application has multilingual content enabled.  See <a href="http://xataface.com/documentation/how-to/how-to-internationalize-your-application">How to Internationalize Your Application</a>
-# Ensure that you have granted the necessary permissions for your users to perform translations.  @see @ref permissions "Permissions"

For more information about internationalization in Xataface:
@see <a href="http://xataface.com/documentation/tutorial/internationalization-with-dataface-0.6">Internationalization with Dataface</a> - An older tutorial on Xataface internationalization.
@see <a href="http://xataface.com/documentation/how-to/how-to-internationalize-your-application">How to Internationalize Your Application</a>



@section toc Table of Contents

-# @ref overview "Architectural Overview"
-# @ref first_translation "Your First Translation"
-# @ref permissions "Setting Up Permissions"
-# @ref troubleshooting "Troubleshooting"
-# @ref roadmap "Road Map"


@section Support

Post support requests in the <a href="http://xataface.com/forum">Xataface Message Forum</a>

@contact

@see <a href="http://solutions.weblite.ca/contact">Web Lite Solutions Corp</a>.

*/?>