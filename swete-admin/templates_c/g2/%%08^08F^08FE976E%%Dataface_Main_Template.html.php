<?php /* Smarty version 2.6.18, created on 2013-07-02 20:07:26
         compiled from Dataface_Main_Template.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'define_slot', 'Dataface_Main_Template.html', 2, false),array('block', 'if_allowed', 'Dataface_Main_Template.html', 119, false),array('block', 'translate', 'Dataface_Main_Template.html', 154, false),array('modifier', 'escape', 'Dataface_Main_Template.html', 2, false),array('modifier', 'count', 'Dataface_Main_Template.html', 129, false),array('modifier', 'nl2br', 'Dataface_Main_Template.html', 185, false),array('function', 'block', 'Dataface_Main_Template.html', 17, false),array('function', 'actions_menu', 'Dataface_Main_Template.html', 55, false),array('function', 'language_selector', 'Dataface_Main_Template.html', 70, false),array('function', 'form_context', 'Dataface_Main_Template.html', 125, false),array('function', 'actions', 'Dataface_Main_Template.html', 128, false),)), $this); ?>
<!doctype html>
<?php $this->_tag_stack[] = array('define_slot', array('name' => 'html_tag')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><html lang="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['language'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>

	<head>
		<?php if (! $this->_tpl_vars['ENV']['prefs']['no_history']): ?>
			<?php 
				$app =& Dataface_Application::getInstance();
				$_SESSION['--redirect'] = $app->url('');
			 ?>
		<?php endif; ?>
		
		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'html_head')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->_tpl_vars['ENV']['APPLICATION']['oe']; ?>
"/>
			<title><?php $this->_tag_stack[] = array('define_slot', array('name' => 'html_title')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['APPLICATION_OBJECT']->getPageTitle())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></title>
			
			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'custom_stylesheets')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><!-- Stylesheets go here --><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'custom_stylesheets2'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'dataface_javascripts')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<script type="text/javascript" language="javascript">
					DATAFACE_URL = '<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
';
					DATAFACE_SITE_URL = '<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_URL']; ?>
';
					DATAFACE_SITE_HREF = '<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
';
				</script>
				<script src="<?php echo $this->_tpl_vars['G2']->getBaseURL(); ?>
/js/xataface-global.js"></script>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php $_from = $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->headContent; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['headContent']):
?>
				<?php echo $this->_tpl_vars['headContent']; ?>

			<?php endforeach; endif; unset($_from); ?>
			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'custom_javascripts')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<!-- custom javascripts can go in slot "custom_javascripts" -->
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			
			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'head_slot')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<!-- Place any other items in the head of the document by filling the "head_slot" slot -->
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head_slot.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'head'), $this);?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>


	</head>
	<body onload="bodyOnload()" <?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'body_atts'), $this);?>
>

		<link rel="alternate" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['APPLICATION_OBJECT']->url('-action=feed&--format=RSS2.0'))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="RSS 2.0" type="application/rss+xml" />

		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_body'), $this);?>

		<?php $this->_tag_stack[] = array('define_slot', array('name' => 'html_body')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><!-- Replace the entire HTML Body with the "html_body" slot -->
			<div id="top-section">
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_header'), $this);?>

				<?php $this->_tag_stack[] = array('define_slot', array('name' => 'global_header')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "global_header.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_header'), $this);?>

				
				<div id="top-menu-bar">
					<div id="top-right-menu-bar">
						<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('category' => 'top_right_menu_bar','maxcount' => 4,'class' => 'right'), $this);?>

					</div>
				
					<div id="top-left-menu-bar">
						<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('category' => 'top_left_menu_bar','maxcount' => 7), $this);?>

					</div>
					<div style="clear:both; height: 1px;"></div>
				
				
				</div>
				
				
				
				<?php if (! $this->_tpl_vars['ENV']['prefs']['hide_language_selector']): ?>
					<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_language_selector'), $this);?>

					<?php $this->_tag_stack[] = array('define_slot', array('name' => 'language_selector')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><div id="language_selector"><?php echo $this->_plugins['function']['language_selector'][0][0]->language_selector(array('autosubmit' => 'true','type' => 'ul','use_flags' => false), $this);?>
</div><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
					<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_language_selector'), $this);?>

				<?php endif; ?>
					
				
			
		
			</div>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_main_table'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'main_table')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<table width="100%" border="0" cellpadding="5" id="main_table">
					<tr>
						<td valign="top" id="left_column">
							<div class="left-column-wrapper">
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_xf_logo'), $this);?>

							<?php $this->_tag_stack[] = array('define_slot', array('name' => 'xf_logo')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
								<div id="xf-logo"><span>Xataface</span></div>
							<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_xf_logo'), $this);?>

							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_left_column'), $this);?>

							<?php $this->_tag_stack[] = array('define_slot', array('name' => 'left_column')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
								
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_table_actions'), $this);?>

								<?php $this->_tag_stack[] = array('define_slot', array('name' => 'table_actions')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
									<div id="xf-table-actions">
										<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('id' => 'table_actions','id_prefix' => "table-actions-",'class' => 'tableActions','category' => 'table_actions'), $this);?>

									</div>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_table_actions'), $this);?>

								
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_table_quicklinks'), $this);?>

								<?php $this->_tag_stack[] = array('define_slot', array('name' => 'table_quicklinks')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
									<div id="xf-table-quicklinks">
										<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('id' => 'table_quicklinks','id_prefix' => "table-quicklinks-",'class' => 'tableQuicklinks','category' => 'table_quicklinks'), $this);?>

									</div>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_table_quicklinks'), $this);?>

								
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_application_menu'), $this);?>

								<?php $this->_tag_stack[] = array('define_slot', array('name' => 'application_menu')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Dataface_Application_Menu.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_application_menu'), $this);?>

			
							<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><!-- left_column-->
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_left_column'), $this);?>

							</div>
						</td>
						<td valign="top" id="main_column">
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_main_column'), $this);?>

							<?php $this->_tag_stack[] = array('define_slot', array('name' => 'main_column')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
								<?php $this->_tag_stack[] = array('if_allowed', array('permission' => 'find','table' => $this->_tpl_vars['ENV']['table'])); $_block_repeat=true;$this->_plugins['block']['if_allowed'][0][0]->if_allowed($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
									<?php if ($this->_tpl_vars['ENV']['prefs']['show_search']): ?>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_search'), $this);?>

										<?php $this->_tag_stack[] = array('define_slot', array('name' => 'search_form')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
											<div class="search_form" id="top-search-form">
												<form method="GET" action="<?php echo $_SERVER['HOST_URI']; ?>
<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
">
													<?php echo $this->_plugins['function']['form_context'][0][0]->form_context(array('exclude' => "-action,-skip,-submit,-mode"), $this);?>

													
													<input class="xf-search-field" type="text" name="-search" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['search'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
													<?php echo $this->_plugins['function']['actions'][0][0]->actions(array('category' => 'find_actions','var' => 'find_actions'), $this);?>

													<?php if (count($this->_tpl_vars['find_actions']) > 1): ?>
														<select name="-action">
														<?php $_from = $this->_tpl_vars['find_actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['find_action']):
?>
															<?php $this->assign('option_label', $this->_tpl_vars['find_action']['label']); ?>
															<?php if (! $this->_tpl_vars['option_label']): ?>
																<?php $this->assign('option_label', $this->_tpl_vars['ENV']['table_object']->getLabel()); ?>
															<?php endif; ?>
															<?php $this->assign('option_value', $this->_tpl_vars['find_action']['action']); ?>
															<?php if (! $this->_tpl_vars['option_value']): ?>
																<?php $this->assign('option_value', $this->_tpl_vars['find_action']['name']); ?>
															<?php endif; ?>
															<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['option_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['option_label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</option>
														<?php endforeach; endif; unset($_from); ?>
														</select>
													<?php else: ?>
														<?php $_from = $this->_tpl_vars['find_actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['find_action']):
?>
															
															<?php $this->assign('option_value', $this->_tpl_vars['find_action']['action']); ?>
															<?php if (! $this->_tpl_vars['option_value']): ?>
																<?php $this->assign('option_value', $this->_tpl_vars['find_action']['name']); ?>
															<?php endif; ?>
															<input type="hidden" name="-action" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['option_value'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/>
														<?php endforeach; endif; unset($_from); ?>
														
													<?php endif; ?>
													<input class="xf-search-button" type="submit" name="-submit" value="<?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.LABEL_SEARCH")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Search<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['table_object']->getLabel())) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" id="search_submit_button" />
													<a class="xf-show-advanced-find">Advanced Search</a>
													<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_search_form_submit'), $this);?>

												</form>
											
											</div>
										<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_search'), $this);?>

									
					
									<?php endif; ?>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['if_allowed'][0][0]->if_allowed($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
											
		
								<?php if ($this->_tpl_vars['back'] && ! $this->_tpl_vars['ENV']['APPLICATION']['hide_back']): ?>
									<div class="browser_nav_bar">
										<a href="<?php echo $this->_tpl_vars['back']['link']; ?>
" title="<?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.LABEL_BACK")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Back<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>">&lt;&lt; <?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.LABEL_GO_BACK")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Go Back<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a>
									</div>
								<?php endif; ?>
			
	
								<div class="horizontalDivider">&nbsp;</div>
			
								<?php $this->_tag_stack[] = array('define_slot', array('name' => 'xataface_notice_messages')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			
									<?php if ($this->_tpl_vars['ENV']['APPLICATION_OBJECT']->numMessages() > 0): ?>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_message'), $this);?>

										<div class="portalMessage">
											<ul>
											<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'message'), $this);?>

											<?php $_from = $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->getMessages(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg']):
?>
												<li><?php echo ((is_array($_tmp=$this->_tpl_vars['msg'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</li>
											<?php endforeach; endif; unset($_from); ?>
											</ul>
										</div>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_message'), $this);?>

									<?php endif; ?>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php $this->_tag_stack[] = array('define_slot', array('name' => 'xataface_error_messages')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
									<?php if ($this->_tpl_vars['ENV']['APPLICATION_OBJECT']->numErrors() > 0): ?>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_errors'), $this);?>

										<div class="portalMessage">
											<h5><?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.HEADING_ERRORS")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Errors<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h5>
											<ul>
												<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'error'), $this);?>

												<?php $_from = $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->getErrors(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['error']):
?>
													<li><?php echo ((is_array($_tmp=$this->_tpl_vars['error']->getMessage())) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</li>
												<?php endforeach; endif; unset($_from); ?>
											</ul>
										</div>
										<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_errors'), $this);?>

									<?php endif; ?>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								
			
								
			
								
								<div class="documentContent mainArea" id="region-content">
								
			
									<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_main_section'), $this);?>

									<?php $this->_tag_stack[] = array('define_slot', array('name' => 'main_section')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
										
										<div style="clear:both">
											<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_record_content'), $this);?>

											<?php $this->_tag_stack[] = array('define_slot', array('name' => 'record_content')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
												<?php echo $this->_tpl_vars['body']; ?>

											<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
											<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_record_content'), $this);?>

									
										</div>
									<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><!-- main_section-->
									<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_main_section'), $this);?>

		
								</div>
								
							<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><!-- main_column-->
						</td>
					</tr>
				</table>
	
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?><!--main_table-->
			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'fineprint')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_fineprint'), $this);?>

				<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "Dataface_Fineprint.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_fineprint'), $this);?>

			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_global_footer'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'global_footer')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "global_footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_global_footer'), $this);?>

		
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?> <!-- html_body -->
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'javascript_tool_includes'), $this);?>

	</body>
</html>