<?php
/**
@page overview "Architectural Overview"

This module aims to complete the that Xataface half-finished with its multilingual support.  Since version 0.6, Xataface has supported databases with multilingual content, but it lacked a translation memory, and its translation form could be a little cumbersome.

This module overrides the default translation form, and introduces a robust, extensible translation memory that resides right inside the same database as your application.

@section translation_form_improvements "Improvements to the Translation Form"

-# <b>Multi-record Form</b> - Rather than translating a single record per form, it allows you to check off multiple records to translate them all simultaneously.
-# <b>String Parsing</b> - Rather than translating a full field at a time, it parses the source content (if it is HTML) into sub-strings that can be translated individually.  This allows the translation memory to remember translations for smaller chunks or text, thereby increasing the likelihood of being able to reuse the strings.
-# <b>Translation Memory</b> - The namesake of this module.  When translating records, the source strings are automatically matched against the translation memory and matches applied to the translation form.


@section translation_memory_vs_record "Translation Memory vs Translated Record"

It is important to understand the distinction between translation memory and the persistent storage of translated content that Xataface has had (since 0.6) and will continue to have moving forward.  

The translation memory stores the individual strings that have been translated, but these strings aren't used directly in the application outside of the translation form.  They are only used to fill in translations on the translation form.  When the translator has translated all of the strings in a record's field, he will click "Save" to persist the translation in the translation table for that record.  If he doesn't explicitly save the field, then the translation won't actually update the record content - though the translations will be stored in the translation memory and can easily be recalled by just loading up the translation form again.

For exmample, suppose we have a multilingual table structure with English and French translations:

Table:  @p pages 
Columns:

- @p page_id INT(11) auto_increment
- @p page_content TEXT

Table: @p pages_en
Columns:

- @p page_id INT(11),
- @p page_content TEXT

Table: @p pages_fr
Columns:

- @p page_id INT(11)
- @p page_content TEXT


When the user loads up the translation form for translating from English to French, it will be parsing the HTML content in the @p page_content field of the pages table into individual strings.  It will then check the translation memory for any matches on those strings and prefill the translation form with those matches if found.  As the user fills in the text fields to provide translations for those individual strings, it will automatically add this input into the translation memory. But it won't yet save anything to the pages_fr table.

Finally when the user clicks "Save", it will unparse the strings but using the translated content instead of the source content, and save this to the page_content field of the @p pages_fr table to correspond with the source record.

@par What If The User Doesn't Click Save

If the user doesn't click "Save" for the field, then his translations won't be saved to the @p pages_fr table, and thus the record won't be updated at all.  However because his translation has been saved to the translation memory, he can easily load up the form later and the form will be prefilled with his translations as the will have been matched against the translation memory.  He can then click "Save" to propagate his translation to the @p pages_fr table.


@section translation_form_flowchart "Flow Chart"

<img src="http://media.weblite.ca/files/photos/tm-flowchart.png?max_width=640"/>


@ref toc


*/?>