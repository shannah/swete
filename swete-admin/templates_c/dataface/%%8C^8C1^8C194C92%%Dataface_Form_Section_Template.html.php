<?php /* Smarty version 2.6.18, created on 2013-07-02 20:11:00
         compiled from Dataface_Form_Section_Template.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'Dataface_Form_Section_Template.html', 1, false),array('modifier', 'count', 'Dataface_Form_Section_Template.html', 130, false),array('function', 'block', 'Dataface_Form_Section_Template.html', 2, false),array('function', 'actions_menu', 'Dataface_Form_Section_Template.html', 174, false),array('block', 'define_slot', 'Dataface_Form_Section_Template.html', 20, false),array('block', 'translate', 'Dataface_Form_Section_Template.html', 68, false),)), $this); ?>
<?php if ($this->_tpl_vars['section']['field']['description']): ?><div class="formHelp"><?php echo ((is_array($_tmp=$this->_tpl_vars['section']['field']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div><?php endif; ?>
<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_quickform_table'), $this);?>

		<table width="100%" class="Dataface_QuickForm-table-wrapper xf-form-group" data-xf-record-id="<?php echo ((is_array($_tmp=$this->_tpl_vars['form_record_id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
		
						<?php $_from = $this->_tpl_vars['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['element']):
?>
			
								
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => "before_".($this->_tpl_vars['element']['field']['name'])."_row",'table' => $this->_tpl_vars['element']['field']['tablename']), $this);?>

				<?php $this->_tag_stack[] = array('define_slot', array('name' => ($this->_tpl_vars['element']['field']['name'])."_row",'table' => $this->_tpl_vars['element']['field']['tablename'])); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
				<tr id="<?php echo $this->_tpl_vars['element']['field']['name']; ?>
_form_row">
					<?php if ($this->_tpl_vars['element']['field']['widget']['template']): ?>
						<td colspan="2">
							<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['element']['field']['widget']['template'], 'smarty_include_vars' => array('element' => $this->_tpl_vars['element'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
						</td>
					<?php else: ?>
						<td <?php if ($this->_tpl_vars['element']['field']['display'] == 'block'): ?>colspan="2" class="Dataface_QuickForm-textarea-label-cell Dataface_QuickForm-block-label-cell"<?php endif; ?> valign="top" <?php if ($this->_tpl_vars['element']['field']['display'] != 'block'): ?>align="right" class="Dataface_QuickForm-label-cell"<?php endif; ?>>
						<div class="field <?php if ($this->_tpl_vars['element']['error']): ?>error<?php endif; ?>" id="<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-label-wrapper">
							
							<label>
								<?php if ($this->_tpl_vars['element']['field']['label_link'] || $this->_tpl_vars['element']['field']['label_click']): ?><a href="<?php if ($this->_tpl_vars['element']['field']['label_link']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['element']['field']['label_link'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>#<?php endif; ?>" target="_blank" <?php if ($this->_tpl_vars['element']['field']['label_click']): ?>onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['element']['field']['label_click'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php endif; ?>class="field-label-link no-link-icon"><?php endif; ?>
									<?php echo $this->_tpl_vars['element']['field']['widget']['label']; ?>

								<?php if ($this->_tpl_vars['element']['field']['label_link'] || $this->_tpl_vars['element']['field']['label_click']): ?></a><?php endif; ?>
							</label>
						
							<?php if ($this->_tpl_vars['element']['required']): ?>
								<span style="color: #ff0000" class="fieldRequired" title="required">&nbsp;</span>
							<?php endif; ?>
						<?php if ($this->_tpl_vars['element']['field']['display'] != 'block'): ?>
						</div>
						</td>
						<td class="Dataface_QuickForm-widget-cell<?php if ($this->_tpl_vars['isText']): ?> Dataface_QuickForm-textarea-widget-cell<?php endif; ?>">
						<div class="field <?php if ($this->_tpl_vars['element']['error']): ?>error<?php endif; ?>" id="<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-wrapper">
						<?php endif; ?>
							<?php if ($this->_tpl_vars['element']['error']): ?>
								<div class="fieldError" style="color: #ff0000"><?php echo $this->_tpl_vars['element']['error']; ?>
</div>
							<?php endif; ?>
						<?php if ($this->_tpl_vars['element']['field']['display'] == 'block'): ?><?php if (! $this->_tpl_vars['element']['frozen']): ?>
							<div class="formHelp"><?php echo $this->_tpl_vars['element']['field']['widget']['description']; ?>
</div>
							<?php else: ?>
							<div class="formHelp"><?php echo $this->_tpl_vars['element']['field']['widget']['frozen_description']; ?>
</div>
							<?php endif; ?>
						<?php endif; ?>
							
						
						<?php if ($this->_tpl_vars['element']['properties']['preview']): ?>
							<div id="<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-preview">
								<?php $this->_tag_stack[] = array('define_slot', array('name' => ($this->_tpl_vars['element']['field']['name'])."_preview_image",'src' => $this->_tpl_vars['element']['properties']['image_preview'])); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
								<?php if ($this->_tpl_vars['element']['properties']['image_preview']): ?>
								
									<img src="<?php echo $this->_tpl_vars['element']['properties']['image_preview']; ?>
" style="display: block; max-height: 200px" alt="<?php echo $this->_tpl_vars['element']['field']['name']; ?>
 preview image"/>
								
								<?php endif; ?>
								<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<script type="text/javascript" language="javascript">
								require(DATAFACE_URL+'/js/delete_file.js');
								</script>
								<?php $this->_tag_stack[] = array('define_slot', array('name' => ($this->_tpl_vars['element']['field']['name'])."_preview_link",'src' => $this->_tpl_vars['element']['properties']['preview'])); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><a href="<?php echo $this->_tpl_vars['element']['properties']['preview']; ?>
" target="_blank"><?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.MESSAGE_VIEW_FIELD_CONTENT")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>View Field Content in new Window<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></a><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php $this->_tag_stack[] = array('define_slot', array('name' => ($this->_tpl_vars['element']['field']['name'])."_delete_link")); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><a class="delete-file-link" href="#" title="Delete this file" onclick="Xataface.deleteFile('<?php echo $this->_tpl_vars['element']['properties']['record_url']; ?>
', '<?php echo $this->_tpl_vars['element']['field']['Field']; ?>
', '<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-preview');return false;"><img src="<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/images/delete.gif" alt="Delete"/></a><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
							</div>
						<?php endif; ?>
						
							<div>
							<?php if ($this->_tpl_vars['element']['field']['widget']['question']): ?>
							<div class="formHelp"><?php echo $this->_tpl_vars['element']['field']['widget']['question']; ?>
</div>
							<?php endif; ?> 
							<?php $this->_tag_stack[] = array('define_slot', array('name' => ($this->_tpl_vars['element']['field']['name'])."_widget",'table' => $this->_tpl_vars['element']['field']['tablename'])); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => "before_".($this->_tpl_vars['element']['field']['name'])."_widget",'table' => $this->_tpl_vars['element']['field']['tablename']), $this);?>

							<?php if ($this->_tpl_vars['element']['html']): ?>
								<?php if ($this->_tpl_vars['element']['type'] == 'submit'): ?>
									<?php $this->_tag_stack[] = array('define_slot', array('name' => 'before_submit_button')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
								<?php endif; ?>
								<?php echo $this->_tpl_vars['element']['html']; ?>

							<?php elseif ($this->_tpl_vars['element']['elements']): ?>
								<?php if ($this->_tpl_vars['element']['field']['widget']['layout'] == 'table'): ?>
									<fieldset>
									<legend>
									<?php if ($this->_tpl_vars['element']['field']['label_link'] || $this->_tpl_vars['element']['field']['label_click']): ?><a href="<?php if ($this->_tpl_vars['element']['field']['label_link']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['element']['field']['label_link'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>#<?php endif; ?>" target="_blank" <?php if ($this->_tpl_vars['element']['field']['label_click']): ?>onclick="<?php echo $this->_tpl_vars['element']['field']['label_click']; ?>
"<?php endif; ?> class="field-label-link no-link-icon"><?php endif; ?>
										<?php echo $this->_tpl_vars['element']['field']['widget']['label']; ?>

									<?php if ($this->_tpl_vars['element']['field']['label_link'] || $this->_tpl_vars['element']['field']['label_click']): ?></a><?php endif; ?>
									</legend>
									<table>
									<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['grouploop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['grouploop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['groupel']):
        $this->_foreach['grouploop']['iteration']++;
?>
										<tr>
											<td align="right" valign="top" class="Dataface_QuickForm-label-cell">
												<div class="field <?php if ($this->_tpl_vars['groupel']['error']): ?>error<?php endif; ?> id="<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-<?php echo $this->_tpl_vars['groupel']['field']['name']; ?>
-label-wrapper">
													<label>
														<?php if ($this->_tpl_vars['groupel']['field']['label_link'] || $this->_tpl_vars['groupel']['field']['label_click']): ?><a href="<?php if ($this->_tpl_vars['groupel']['field']['label_link']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['groupel']['field']['label_link'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>#<?php endif; ?>" target="_blank" <?php if ($this->_tpl_vars['groupel']['field']['label_click']): ?>onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['groupel']['field']['label_click'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?> class="field-label-link no-link-icon"><?php endif; ?>
															<?php echo $this->_tpl_vars['groupel']['field']['widget']['label']; ?>

														<?php if ($this->_tpl_vars['groupel']['field']['label_link'] || $this->_tpl_vars['groupel']['field']['label_click']): ?></a><?php endif; ?>
													</label>
													<?php if ($this->_tpl_vars['groupel']['required']): ?>
														<span style="color: #ff0000" class="fieldRequired" title="required">&nbsp;</span>
													<?php endif; ?>
													
												</div>
											</td>
											
											<td class="Dataface_QuickForm-widget-cell">
												<div class="field <?php if ($this->_tpl_vars['groupel']['error']): ?>error<?php endif; ?> id="<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
-<?php echo $this->_tpl_vars['element']['field']['name']; ?>
-<?php echo $this->_tpl_vars['groupel']['field']['name']; ?>
-wrapper">
												<?php if ($this->_tpl_vars['groupel']['error']): ?>
													<div class="fieldError" style="color: #ff0000"><?php echo $this->_tpl_vars['groupel']['error']; ?>
</div>
													
												<?php endif; ?>
												<?php if ($this->_tpl_vars['groupel']['type'] == 'submit'): ?>
													<?php $this->_tag_stack[] = array('define_slot', array('name' => 'before_submit_button')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
												<?php endif; ?>
												<?php echo $this->_tpl_vars['groupel']['html']; ?>

													<div class="formHelp"><?php echo $this->_tpl_vars['groupel']['field']['widget']['description']; ?>
</div>
												</div>
											</td>
										</tr>
									<?php endforeach; endif; unset($_from); ?>
									</table>
									</fieldset>
								<?php else: ?>
									<!--<fieldset><legend><?php echo $this->_tpl_vars['element']['field']['widget']['label']; ?>
</legend>-->
									<?php if ($this->_tpl_vars['element']['field']['widget']['columns']): ?><?php $this->assign('cols', $this->_tpl_vars['element']['field']['widget']['columns']); ?><?php else: ?><?php $this->assign('cols', 3); ?><?php endif; ?>
									<?php if ($this->_tpl_vars['cols'] > 1): ?>										<?php $this->assign('numelements', count($this->_tpl_vars['element']['elements'])); ?>
										<?php $this->assign('threshold', $this->_tpl_vars['numelements']/$this->_tpl_vars['cols']); ?>
										<table><tr><td>
									<?php endif; ?>
									<?php $this->assign('ctr', 0); ?>
									<?php $_from = $this->_tpl_vars['element']['elements']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['grouploop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['grouploop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['groupel']):
        $this->_foreach['grouploop']['iteration']++;
?>
										<?php if ($this->_tpl_vars['groupel']['field']['label_link'] || $this->_tpl_vars['groupel']['field']['label_click']): ?><a href="<?php if ($this->_tpl_vars['groupel']['field']['label_link']): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['groupel']['field']['label_link'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>#<?php endif; ?>" target="_blank" <?php if ($this->_tpl_vars['groupel']['field']['label_click']): ?>onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['groupel']['field']['label_click'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?> class="field-label-link no-link-icon"><?php endif; ?>
											<?php if ($this->_tpl_vars['groupel']['field']['widget']['label']): ?><?php echo $this->_tpl_vars['groupel']['field']['widget']['label']; ?>
<?php else: ?><?php echo $this->_tpl_vars['groupel']['label']; ?>
<?php endif; ?>
										<?php if ($this->_tpl_vars['groupel']['field']['label_link'] || $this->_tpl_vars['groupel']['field']['label_click']): ?></a><?php endif; ?>
										<?php if ($this->_tpl_vars['groupel']['type'] == 'submit'): ?>
											<?php $this->_tag_stack[] = array('define_slot', array('name' => 'before_submit_button')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?><?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
										<?php endif; ?>
										<?php echo $this->_tpl_vars['groupel']['html']; ?>

										<?php if ($this->_tpl_vars['groupel']['field']['widget']['description']): ?><span class="formHelp"><?php echo $this->_tpl_vars['groupel']['field']['widget']['description']; ?>
</span><?php endif; ?>
										<?php if ($this->_tpl_vars['element']['field']['widget']['separator']): ?><?php echo $this->_tpl_vars['element']['field']['widget']['separator']; ?>
<?php else: ?><?php echo $this->_tpl_vars['element']['separator']; ?>
<?php endif; ?>
										<?php $this->assign('ctr', $this->_tpl_vars['ctr']+1); ?>
										<?php if (( $this->_tpl_vars['cols'] > 1 ) && ( $this->_tpl_vars['ctr'] >= $this->_tpl_vars['threshold'] )): ?></td><td><?php $this->assign('ctr', 0); ?><?php endif; ?>
									<?php endforeach; endif; unset($_from); ?>
									<?php if ($this->_tpl_vars['cols'] > 1): ?>
										</td></tr></table>
									<?php endif; ?>
									<!--</fieldset>--> 
								<?php endif; ?>
							<?php endif; ?>
							<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => "after_".($this->_tpl_vars['element']['field']['name'])."_widget",'table' => $this->_tpl_vars['element']['field']['tablename']), $this);?>

							<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
														<?php if ($this->_tpl_vars['element']['field']['widget']['type'] == 'select' && $this->_tpl_vars['element']['field']['widget']['editvalues']): ?>
								<script language="javascript"><!--
								require('<?php echo $this->_tpl_vars['ENV']['DATAFACE_URL']; ?>
/js/ajax.js');
								makeSelectEditable('<?php echo $this->_tpl_vars['element']['field']['tablename']; ?>
', '<?php echo $this->_tpl_vars['element']['field']['vocabulary']; ?>
', document.getElementById('<?php echo $this->_tpl_vars['element']['field']['name']; ?>
'));
								//--></script>
							<?php endif; ?>
														<?php if ($this->_tpl_vars['element']['field']['widget']['suffix']): ?>
								<?php echo $this->_tpl_vars['element']['field']['widget']['suffix']; ?>

							<?php endif; ?>
							
							<?php if ($this->_tpl_vars['element']['properties']['link']): ?>
								<a href="<?php echo $this->_tpl_vars['element']['properties']['link']; ?>
">Go</a>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['element']['field']['actions']): ?>
								<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('class' => 'field_actions','id' => "field_actions-".($this->_tpl_vars['element']['field']['name']),'category' => $this->_tpl_vars['element']['field']['actions'],'var' => 'actions'), $this);?>

							<?php endif; ?>
							
							</div>
						<?php if ($this->_tpl_vars['element']['field']['display'] != 'block'): ?>
							<?php if (! $this->_tpl_vars['element']['frozen']): ?>
							<div class="formHelp"><?php echo $this->_tpl_vars['element']['field']['widget']['description']; ?>
</div>
							<?php else: ?>
							<div class="formHelp"><?php echo $this->_tpl_vars['element']['field']['widget']['frozen_description']; ?>
</div>
							<?php endif; ?>
						<?php endif; ?>
							<?php if ($this->_tpl_vars['element']['field']['widget']['focus']): ?>
							<script language="javascript" type="text/javascript"><!--
							try<?php echo '{'; ?>
quickForm.setFocus('<?php echo $this->_tpl_vars['element']['field']['name']; ?>
');<?php echo '} catch(err){}'; ?>

							//--></script>
							
							
							<?php endif; ?>
						</div>
						</td>
					<?php endif; ?>
				</tr>
				<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => "after_".($this->_tpl_vars['element']['field']['name'])."_row",'table' => $this->_tpl_vars['element']['field']['tablename']), $this);?>

			<?php endforeach; endif; unset($_from); ?>
		</table>