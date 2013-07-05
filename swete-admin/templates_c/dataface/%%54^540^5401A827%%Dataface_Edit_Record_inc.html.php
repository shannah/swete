<?php /* Smarty version 2.6.18, created on 2013-07-04 19:54:01
         compiled from Dataface_Edit_Record_inc.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'Dataface_Edit_Record_inc.html', 4, false),array('modifier', 'truncate', 'Dataface_Edit_Record_inc.html', 5, false),array('modifier', 'count', 'Dataface_Edit_Record_inc.html', 12, false),array('block', 'translate', 'Dataface_Edit_Record_inc.html', 7, false),)), $this); ?>


<?php $this->assign('record', $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->getRecord()); ?>
<?php $this->assign('tableSingularLabel', ((is_array($_tmp=$this->_tpl_vars['ENV']['table_object']->getSingularLabel())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp))); ?>
<?php $this->assign('recordTitle', ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['record']->getTitle())) ? $this->_run_mod_handler('truncate', true, $_tmp, 50) : smarty_modifier_truncate($_tmp, 50)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp))); ?>
<h3>
    <?php $this->_tag_stack[] = array('translate', array('id' => 'Edit Record Form Heading','tablename' => $this->_tpl_vars['tableSingularLabel'],'recordTitle' => $this->_tpl_vars['recordTitle'])); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
        Edit <?php echo $this->_tpl_vars['tableSingularLabel']; ?>
 &raquo; <?php echo $this->_tpl_vars['recordTitle']; ?>

    <?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
</h3>

<?php if ($this->_tpl_vars['tabs'] && count($this->_tpl_vars['tabs']) > 1): ?>
    <div id="edit-record-tabs-container" class="tabs-container">
        <ul id="edit-record-tabs" class="tabs-nav">

            <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tab']):
?>
                    <li class="<?php echo $this->_tpl_vars['tab']['css_class']; ?>
"><a href="<?php echo $this->_tpl_vars['tab']['url']; ?>
"><?php echo $this->_tpl_vars['tab']['label']; ?>
</a><span></span></li>
            <?php endforeach; endif; unset($_from); ?>
        </ul>
        <script language="javascript">
            var head = document.getElementsByTagName('head');
            var css = document.createElement('link');
            css.type='text/css';
            css.rel='stylesheet';
            css.href='<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/css/jquery.tabs.css';
            head[0].appendChild(css);
        </script>
        <!--[if lte IE 7]>
        <script language="javascript">
        var head = document.getElementsByTagName('head');
        var css = document.createElement('link');
        css.type='text/css';
        css.rel='stylesheet';
        css.href='<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/css/jquery.tabs-ie.css';
        head[0].appendChild(css);
        </script>
        <![endif]-->
    </div>

    <div class="tabs-panel">
        <div><?php $this->_tag_stack[] = array('translate', array('id' => 'Remember to press save')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Remember to press <em>Save</em> when you're done.<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
<?php endif; ?>
<?php echo $this->_tpl_vars['form']; ?>

<?php if ($this->_tpl_vars['tabs'] && count($this->_tpl_vars['tabs']) > 1): ?>
    </div><!-- tabs-panel -->
<?php endif; ?>