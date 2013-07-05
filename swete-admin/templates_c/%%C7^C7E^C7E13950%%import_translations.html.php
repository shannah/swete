<?php /* Smarty version 2.6.18, created on 2013-07-04 21:05:27
         compiled from swete/actions/import_translations.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'use_macro', 'swete/actions/import_translations.html', 1, false),array('block', 'fill_slot', 'swete/actions/import_translations.html', 2, false),)), $this); ?>
<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <?php $this->_tag_stack[] = array('fill_slot', array('name' => 'main_column')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
    <h1>Import Translations</h1>
    
    <div id="import-form-progress" style="display:none">
        Importing translations. Please wait ...<img src="<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/images/progress.gif"/>
    </div>
    
    <div id="import-form-wrapper"></div>
    
    <div id="import-form-results-wrapper" style="display:none">
        <h3>Import is Complete</h3>
        
        <p class="succeeded-p"><span data-kvc="succeeded">x</span> strings were successfully imported.</p>
        <p class="failed-p"><span data-kvc="failed">y</span> strings could not be imported.  Check Import log for details.</p>
        <h4>Import log</h4>
        
        <textarea class="import-log" rows="8" data-kvc="log"></textarea>
        
        <div class="buttons">
            <button id="view-strings-btn" title="View strings in the imported translation memory">View Translations</button>
            <button id="import-more-strings-btn" title="Import another file">Import Another Translation File</button>
        </div>
        
    </div>
    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>