<?php /* Smarty version 2.6.18, created on 2013-07-03 00:02:33
         compiled from Dataface_Record_Template.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'load_record', 'Dataface_Record_Template.html', 20, false),array('function', 'actions_menu', 'Dataface_Record_Template.html', 26, false),array('function', 'block', 'Dataface_Record_Template.html', 29, false),array('function', 'record_tabs', 'Dataface_Record_Template.html', 58, false),array('block', 'use_macro', 'Dataface_Record_Template.html', 21, false),array('block', 'fill_slot', 'Dataface_Record_Template.html', 23, false),array('block', 'define_slot', 'Dataface_Record_Template.html', 37, false),array('modifier', 'escape', 'Dataface_Record_Template.html', 50, false),)), $this); ?>
<?php echo $this->_plugins['function']['load_record'][0][0]->load_record(array(), $this);?>

<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'main_section')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<div class="xf-button-bar">
			<div class="result-list-actions list-actions xf-button-bar-actions">
				<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('id' => "record-actions",'id_prefix' => "record-actions-",'category' => 'record_actions','mincount' => 1,'maxcount' => 7), $this);?>

			</div>
			<?php if ($this->_tpl_vars['ENV']['prefs']['show_result_controller']): ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_details_controller'), $this);?>

				<div id="details-controller" class="details-controller"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Dataface_Details_Controller.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></div>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_details_controller'), $this);?>

			<?php endif; ?>
			
			<div class="xf-button-bar-info"></div>
		</div>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_heading'), $this);?>

		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_heading')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>

			
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_heading'), $this);?>

		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_tabs'), $this);?>

		
		
		<div class="documentContent" id="region-content" >
		
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_heading'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_heading')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<div class="dataface-sections-top <?php if ($this->_tpl_vars['ENV']['prefs']['hide_record_view_logo']): ?>dataface-sections-top-no-logo<?php endif; ?>">
					<h3><?php if ($this->_tpl_vars['ENV']['record']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['record']->getTitle())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?></h3>
					
				</div>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_heading'), $this);?>

			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_subtabs'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_subtabs')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<div class="record-subtabs">
					<?php echo $this->_plugins['function']['record_tabs'][0][0]->record_tabs(array('mincount' => 2,'maxcount' => 7), $this);?>

					<div style="clear:both; height:1px;"></div>
				</div>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_subtabs'), $this);?>

		
		
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_content'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_content')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				Record Content goes here ...
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_content'), $this);?>

		</div>

		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_footer'), $this);?>

		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_footer')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_footer'), $this);?>

		
	
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>