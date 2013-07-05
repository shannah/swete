<?php /* Smarty version 2.6.18, created on 2013-07-02 20:07:35
         compiled from Dataface_List_View.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'block', 'Dataface_List_View.html', 23, false),array('function', 'result_list', 'Dataface_List_View.html', 25, false),array('function', 'actions_menu', 'Dataface_List_View.html', 43, false),array('block', 'define_slot', 'Dataface_List_View.html', 24, false),array('block', 'use_macro', 'Dataface_List_View.html', 33, false),array('block', 'fill_slot', 'Dataface_List_View.html', 35, false),array('block', 'translate', 'Dataface_List_View.html', 36, false),array('modifier', 'escape', 'Dataface_List_View.html', 60, false),array('modifier', 'truncate', 'Dataface_List_View.html', 61, false),)), $this); ?>
<?php $this->assign('outputType', "--outputType"); ?>
<?php if ($_REQUEST[$this->_tpl_vars['outputType']] == 'ajax'): ?>
	<div id="list-window" class="result-list-window">
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_result_list_content'), $this);?>

		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'result_list_content')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php echo $this->_plugins['function']['result_list'][0][0]->result_list(array(), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_result_list_content'), $this);?>

		
		

	</div>
<?php else: ?>
	<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	
		<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'main_section')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<?php if ($this->_tpl_vars['error']): ?><div id="error"><?php $this->_tag_stack[] = array('translate', array('id' => $this->_tpl_vars['error_i18n'])); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo $this->_tpl_vars['error']; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div><?php endif; ?>
			
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_result_list'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'result_list')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<div class="result-list-header result-list-bar xf-button-bar">
					<div class="result-list-actions list-actions xf-button-bar-actions">
						<?php ob_start(); ?>
							<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('id' => "list-actions",'id_prefix' => "list-actions-",'category' => 'result_list_actions','maxcount' => 7), $this);?>

						<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('result_list_actions_html', ob_get_contents());ob_end_clean(); ?>
						<?php echo $this->_tpl_vars['result_list_actions_html']; ?>

					</div>
					<?php ob_start(); ?>
						<?php if (! $this->_tpl_vars['ENV']['prefs']['hide_resultlist_controller']): ?>
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Dataface_ResultListController.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						<?php endif; ?>
					<?php $this->_smarty_vars['capture']['default'] = ob_get_contents();  $this->assign('resultlist_controller_html', ob_get_contents());ob_end_clean(); ?>
					<?php echo $this->_tpl_vars['resultlist_controller_html']; ?>

					<div class="search-info xf-button-bar-info">
						<?php $this->assign('search_parameters', $this->_tpl_vars['G2']->getSearchParameters()); ?>
						<?php if ($this->_tpl_vars['search_parameters']): ?>
							<span class="search-results-for">Search Results For: </span>
							<ul class="search-parameters">
								<?php $_from = $this->_tpl_vars['search_parameters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['skey'] => $this->_tpl_vars['sval']):
?>
									<li>
										<a title="<?php echo ((is_array($_tmp=$this->_tpl_vars['skey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
=&quot;<?php echo ((is_array($_tmp=$this->_tpl_vars['sval'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&quot;" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['APPLICATION_OBJECT']->url('-action=find'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
											<span class="search-key"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['skey'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> <span class="search-value"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['sval'])) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
										</a>
									</li>
								<?php endforeach; endif; unset($_from); ?>
							</ul>
							<div style="height: 1px; clear:both"></div>
						<?php endif; ?>
					</div>
				</div>
			
				<div id="list-window" class="result-list-window">
					<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_result_list_content'), $this);?>

					<?php $this->_tag_stack[] = array('define_slot', array('name' => 'result_list_content')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
						<?php echo $this->_plugins['function']['result_list'][0][0]->result_list(array(), $this);?>

					<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_result_list_content'), $this);?>

					
					
	
				</div>
				<div class="result-list-footer result-list-bar xf-button-bar">
					<div class="result-list-actions xf-button-bar-actions">
						<?php echo $this->_tpl_vars['result_list_actions_html']; ?>

					</div>
					<?php echo $this->_tpl_vars['resultlist_controller_html']; ?>

					<div class="search-info xf-button-bar-info"></div>
				</div>
				
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_result_list'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php endif; ?>