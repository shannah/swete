<?php /* Smarty version 2.6.18, created on 2013-07-02 20:11:00
         compiled from Dataface_New_Record.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'use_macro', 'Dataface_New_Record.html', 20, false),array('block', 'fill_slot', 'Dataface_New_Record.html', 21, false),array('block', 'define_slot', 'Dataface_New_Record.html', 24, false),array('function', 'block', 'Dataface_New_Record.html', 23, false),array('function', 'actions_menu', 'Dataface_New_Record.html', 29, false),array('modifier', 'escape', 'Dataface_New_Record.html', 37, false),array('modifier', 'count', 'Dataface_New_Record.html', 39, false),)), $this); ?>
<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'main_section')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_new_record_form'), $this);?>

		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'new_record_form')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		
		<?php ob_start(); ?>
			<div class="xf-button-bar">
				<div class="xf-button-bar-actions">
					<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('category' => 'new_record_form_actions','maxcount' => 7), $this);?>

				</div>
				<div style="clear:both; height: 1px;"></div>
			</div>
			
		<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('new_record_button_bar', ob_get_contents());ob_end_clean(); ?>
		<?php echo $this->_tpl_vars['new_record_button_bar']; ?>

		
		<h3>Insert New <?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['table_object']->getSingularLabel())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
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
			<div>Remember to press <em>Save</em> when you're done.</div>
		<?php endif; ?>
		<?php echo $this->_tpl_vars['form']; ?>

		<?php if ($this->_tpl_vars['tabs'] && count($this->_tpl_vars['tabs']) > 1): ?>
			</div><!-- tabs-panel -->
		<?php endif; ?>
		
		<?php echo $this->_tpl_vars['new_record_button_bar']; ?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_new_record_form'), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>