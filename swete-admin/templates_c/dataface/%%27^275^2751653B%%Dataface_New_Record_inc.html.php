<?php /* Smarty version 2.6.18, created on 2013-07-04 19:56:06
         compiled from Dataface_New_Record_inc.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', 'Dataface_New_Record_inc.html', 2, false),array('modifier', 'escape', 'Dataface_New_Record_inc.html', 7, false),)), $this); ?>

<?php if ($this->_tpl_vars['tabs'] && count($this->_tpl_vars['tabs']) > 1): ?>
    <div id="edit-record-tabs-container" class="tabs-container">
        <ul id="edit-record-tabs" class="tabs-nav">

        <?php $_from = $this->_tpl_vars['tabs']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['tab']):
?>
                <li class="<?php echo ((is_array($_tmp=$this->_tpl_vars['tab']['css_class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['tab']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['tab']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
        <div>Remember to press <em>Save</em> when you're done.</div>
<?php endif; ?>
<?php echo $this->_tpl_vars['form']; ?>

<?php if ($this->_tpl_vars['tabs'] && count($this->_tpl_vars['tabs']) > 1): ?>
    </div><!-- tabs-panel -->
<?php endif; ?>