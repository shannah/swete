(function(){
    var $ = jQuery;
    $(document).ready(function(){
        var processing = false;
    
        $('#xliff-validate-btn').click(function(){
            if ( processing ){
                if ( !confirm('Currently processing request.  Cancel previous request?')){
                    return;
                }
            }
            processing = true;
            $('#processing-xliff').show();
            var q = {
                '-action' : 'tm_validate_xliff',
                '--xliff-content' : $('#xliff-content').val()
            };
            $.post(DATAFACE_SITE_HREF, q, function(res){
                processing = false;
                $('#processing-xliff').hide();
                $('#xliff-results').show();
                if ( res && res.code == 200 ){
                    $('#validation-results').val(res.message);
                } else if ( res.message ){
                    $('#validation-results').val(res.message+"\n"+res.errors);
                }
            });
        });
    });
})();