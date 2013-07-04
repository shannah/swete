/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
//require <xataface/modules/uitk/components/UIForm.js>
//require <xataface/view/View.js>
//require <xataface/store/Document.js>
//require <xataface/model/Model.js>
//require-css <swete/actions/import_translations.css>
(function(){
    var $ = jQuery;
    var UIForm = xataface.modules.uitk.components.UIForm;
    var View = xataface.view.View;
    var Document = xataface.store.Document;
    var Model = xataface.model.Model;
    
    var resultsView = new View({
        model : new Model(),
        el : $('#import-form-results-wrapper').get(0),
        _update : function(){
            View.prototype._update.call(this);
        },
        _decorate : function(){
            View.prototype._decorate.call(this);
            
            
        },
        _undecorate : function(){
            View.prototype._undecorate.call(this);
            
        }
                
    });
    
    $('#view-strings-btn').click(function(){
        
        if ( resultsView.model.get('target_translation_memory_uuid') ){
            window.location=DATAFACE_SITE_HREF+
                '?-action=list'+
                '&-table=translation_miss_log'+
                '&translation_memory_uuid=='+encodeURIComponent(resultsView.model.get('target_translation_memory_uuid'))+
                '&-sort=date_inserted+desc'+
                ((parseInt(resultsView.model.get('succeeded'))>0)?('&-limit='+encodeURIComponent(resultsView.model.get('succeeded'))):'')
                ;
        } else {
            window.location=DATAFACE_SITE_HREF+
                '?-action=list'+
                '&-table=translation_miss_log'+
                '&-sort=date_inserted+desc'+
                (parseInt(resultsView.model.get('succeeded'))>0?('&-limit='+encodeURIComponent(resultsView.model.get('succeeded'))):'');
        }
        
        return false;
    });
    
    $('#import-more-strings-btn').click(function(){
        $('#import-form-results-wrapper').hide();
        $('#import-form-wrapper').show();
        importForm.refresh();
        return false;
    });
    
    
    var resultsLoader = new Document({
        model : resultsView.model,
        query : {
            '-action' : 'export_json',
            '-table' : 'string_imports'
        }
    });
    
    var importForm = new UIForm({
        fields : [
            'file',
            'file_format',
            'target_translation_memory_uuid'
        ],
        table : 'string_imports',
        isNew : true,
        showHeadings : false,
        showSubheadings : false,
        cancelAction : function(){
            window.history.back();
        }
    });
    
    $('#import-form-wrapper').append(importForm.el);
    
    
    $(importForm.el)
        .css({
            'border' : '3px solid rgb(187,204,255)',
            'border-radius' : '15px',
            'width' : '100%',
            'height' : '400px'
        });
    $(importForm)
        .bind('beforeSubmit', function(evt, data){
            if ( data.code == 200 ){
                $('#import-form-progress').show();
                $('#import-form-wrapper').hide();
            }
        });
        
    
    $(importForm).bind('afterSave', function(){
       resultsLoader.query['--recordid'] = importForm.getRecordId();
       
       resultsLoader.load(function(){
           $('#import-form-progress').hide();
           resultsView.update();
           $('#import-form-results-wrapper').fadeIn();
       });
       
    });
    
    importForm.refresh();
    
    
    
})();

