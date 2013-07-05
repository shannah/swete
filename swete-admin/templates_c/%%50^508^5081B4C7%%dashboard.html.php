<?php /* Smarty version 2.6.18, created on 2013-07-02 20:07:30
         compiled from swete/actions/dashboard.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'use_macro', 'swete/actions/dashboard.html', 1, false),array('block', 'fill_slot', 'swete/actions/dashboard.html', 2, false),array('modifier', 'escape', 'swete/actions/dashboard.html', 16, false),)), $this); ?>
<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'left_column')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'head_slot')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_URL']; ?>
/css/swete/actions/dashboard.css"/>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'main_section')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<h1 class="logo"><span>SWeTE Server</span></h1>
		
		
		<div id="summary-stats" class="block">
			<h2>Summary</h2>
			<table>
				<tr>
					<th>Websites</th><td><?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['numSites'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
				</tr>
				<?php if ($this->_tpl_vars['ENV']['APPLICATION']['enable_static']): ?>
					<tr>
						<th>Static Webpages</th><td><?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['numPages'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</td>
					</tr>
				<?php endif; ?>
				<tr>
					<th>Phrases</th><td><span>Translated: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['translatedPhrases'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>,<br/> <span>Untranslated: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['untranslatedPhrases'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>, <br/><span>Total: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['numPhrases'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
				</tr>
				<tr>
					<th>Words</th><td><span>Translated: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['translatedWords'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>,<br/> <span>Untranslated: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['untranslatedWords'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>, <br/><span>Total: <?php echo ((is_array($_tmp=$this->_tpl_vars['systemStats']['numWords'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span></td>
				</tr>
				
			</table>
		</div>
		<div class="block" id="websites-block">
		
			<h2>Websites</h2>
			<table>
			<?php $_from = $this->_tpl_vars['results']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['row']):
?>
				<tr>
					<th><?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_name)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</th>
					<td>
						<a target="_blank" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_url)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->source_label)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						</a>
					</td>
					<td>
						<a target="_blank" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->proxy_url)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
							<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->target_label)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

						</a>
					</td>
					<td>
						<ul class="dropdown-menu">
							<li class="actions-menu-item-wrapper">
								<a href="#" class="actions-menu-item">
									<span>Actions</span>
								</a>
								<ul class="menuitems">
									<li>
										<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=edit&amp;-table=websites&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Edit</a>
									</li>
									<li>
										<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=swete_tool_bar&amp;-table=websites&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Capture Strings</a>
									</li>
									<li>
										<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">Translate Strings</a>
									</li>
								</ul>
							</li>
						</ul>
						
						
					</td>
					<td>
						<a href="#" class="site-stats" title="View the stats for this site"><span>Info</span></a>
						<div class="popup-panel site-stats-panel">
							<div class="panel-bar">
								<a class="close-panel" href="#"><span>Close</span></a>
							</div>
							<div class="content-pane">
								<table>
									<?php if ($this->_tpl_vars['ENV']['APPLICATION']['enable_static']): ?>
										<tr>
											<th># Pages</th>
											<td class="right">
												<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=webpages&website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
													<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->numpages)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

												</a>
											</td>
										</tr>
									<?php endif; ?>
									<tr>
										<th># Phrases</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->numphrases)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
									<tr>
										<th># Words</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->numwords)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
									<tr>
										<th># Translated Phrases</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;normalized_translation_value=&gt;">	
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->translated_phrases)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
									<tr>
										<th># Untranslated Phrases</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;normalized_translation_value==">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->untranslated_phrases)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
									<tr>
										<th># Translated Words</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;normalized_translation_value=&gt;">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->translated_words)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
									<tr>
										<th># Untranslated Words</th>
										<td class="right">
											<a href="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-action=list&amp;-table=translation_miss_log&amp;website_id=<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->website_id)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
&amp;normalized_translation_value==">
												<?php echo ((is_array($_tmp=$this->_tpl_vars['row']->untranslated_words)) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>

											</a>
										</td>
									</tr>
										
								</table>
							</div>
						
						</div>
					</td>
					<td>
						<?php if ($this->_tpl_vars['row']->log_translation_misses): ?>
							<a href="#" class="log-translation-miss-warning" title="String capturing is currently enabled.  Please disable this as soon as you have finished capturing strings."><span>Warning</span></a>
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; endif; unset($_from); ?>
			</table>
			<div class="buttons">
				<button id="create-new-website" onclick="window.location.href='<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
?-table=websites&amp;-action=new';">Create New Website</button>
			</div>
		</div>
	
		
	
		<div style="clear:both">&nbsp;</div>
		<div class="swete-version">
			Currently running SWeTE version <?php echo $this->_tpl_vars['swete_version']; ?>

		</div>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>