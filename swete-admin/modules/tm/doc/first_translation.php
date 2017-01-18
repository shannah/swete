<?php
/**

@page first_translation Your First Translation

Now that you have your application set up for <a href="http://xataface.com/documentation/tutorial/internationalization-with-dataface-0.6">multiple languages</a> and you have @ref Installation "installed" the Translation Memory module, you're ready to jump in with your first translation.

@par Step 1: Select Records To Translate

The first thing you need to do is find the records you want to translate.  In the list view you should be able to check off the box beside the records you wish to translate.

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-10-04_at_3.27.50_PM.png?max_width=640"/>


@par Step 2: Click "Translate"

With the records selected, click the "Translate" button along the top bar of the list view:

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-10-04_at_3.28.04_PM.png?max_width=640"/>


@par Step 3: You should see the translation form load up.  (In Internet Explorer this may take a while as String parsing on this browser is quite slow).

<img src="http://media.weblite.ca/files/photos/Screen_shot_2011-10-04_at_4.59.22_PM.png?max_width=640"/>

@par Step 4: Change The Target Language

Notice the top of the translation form where the heading says "Translate records from XXXX to YYYY" where XXXX is the language that you are currently browsing the application in, and YYYY is a target language.  If you this information is correct, you may continue to step 5.  If this is not the language pair you intend to translate, then you can click the "Change" link beside this header to select a different target language.

@par Step 5: Fill in the Translation for the First Field

Starting at the top of the translation form, you should see a heading for the first record you have chosen.  This will be followed by a section with a highlighted tab named after the first field requiring translation in that record.  There may be more than one text field set up for you to complete the translation.  This is bebause the contents of that field have been split up into smaller substrings to make it easier for you to translate them.  The source text to be translated is displayed just above the text field where you should enter the translation.  This field will likely just contain the source text again.  Just overwrite this with your own translation, and tab to the next field.

@par Step 6: Save the Translation

After you have completed the translation for a field, you should click the "Save" button at the top of the field section.  This will effectively save your translation to the database.  Notice that the translation form for that field will also be collapsed and instead show you a side-by-side comparison of the full source text of the field with the full translated text.  If you want to modify the translation again, simply click the small "Edit" link beside the field label in the left column.


@ref toc




*/?>