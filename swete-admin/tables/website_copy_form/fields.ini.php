;<?php exit;
[website_id]
    widget:type=select
    widget:description="Please select the website profile that you wish to copy"
    widget:label="Website"
    vocabulary=websites
    
[use_same_translation_memory]
    widget:type=checkbox
    widget:description="Check here if you would like the site copy to share the same translation memory as the original site."
    widget:label="Share Translation Memory"
    
[website_name]
    widget:description="The name for the new website profile."
    
[website_url]
    widget:description="The URL that the new website should proxy.  E.g. http://www.example.com/"
    
[copy_tm_strings]
    widget:type=checkbox
    widget:description="Check here to copy all of the translations in the source website into the copied website.  This is ignored if 'Share Translation Memory' has been selected."
    

    