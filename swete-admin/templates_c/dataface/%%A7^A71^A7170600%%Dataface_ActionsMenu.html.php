<?php /* Smarty version 2.6.18, created on 2013-07-02 20:07:30
         compiled from Dataface_ActionsMenu.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'define_slot', 'Dataface_ActionsMenu.html', 20, false),array('modifier', 'count', 'Dataface_ActionsMenu.html', 21, false),array('modifier', 'escape', 'Dataface_ActionsMenu.html', 22, false),array('function', 'block', 'Dataface_ActionsMenu.html', 23, false),)), $this); ?>
<?php $this->_tag_stack[] = array('define_slot', array('name' => 'actions_menu')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
<?php if (count($this->_tpl_vars['actions']) > 0): ?>
<ul <?php if ($this->_tpl_vars['class']): ?>class="<?php echo ((is_array($_tmp=$this->_tpl_vars['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['id']): ?>id="<?php echo ((is_array($_tmp=$this->_tpl_vars['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>>
	<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'actions_menu_head'), $this);?>

<?php $_from = $this->_tpl_vars['actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['name'] => $this->_tpl_vars['action']):
?>
            <?php if ($this->_tpl_vars['action']['subcategory'] && ! $this->_tpl_vars['action']['subactions']): ?>
            	<!-- Omit action because it has no subactions -->
            <?php else: ?>
              <li id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo $this->_tpl_vars['action']['id']; ?>
"
              	  class="
              	  <?php if ($this->_tpl_vars['action']['name'] == $this->_tpl_vars['selected_action'] || $this->_tpl_vars['action']['selected']): ?>selected <?php echo ((is_array($_tmp=$this->_tpl_vars['action']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php else: ?>plain <?php echo ((is_array($_tmp=$this->_tpl_vars['action']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?>
              	  <?php if ($this->_tpl_vars['action']['subactions']): ?>xf-dropdown<?php endif; ?>
              	  <?php $_from = $this->_tpl_vars['action']['atts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['actionAttName'] => $this->_tpl_vars['actionAttValue']):
?><?php echo ((is_array($_tmp=$this->_tpl_vars['actionAttName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
="<?php echo ((is_array($_tmp=$this->_tpl_vars['actionAttValue'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php endforeach; endif; unset($_from); ?>
              	  ">
                
                <a class="<?php if ($this->_tpl_vars['action']['subactions']): ?>trigger<?php endif; ?>" id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
-link" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['action']['onclick']): ?> onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['onclick'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
                   accesskey="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['accessKey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-permission="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['permission'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
                   <?php if ($this->_tpl_vars['action']['confirm']): ?>data-xf-confirm-message="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['confirm'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
                   >
                   <?php if ($this->_tpl_vars['action']['icon']): ?><img id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo $this->_tpl_vars['action']['id']; ?>
-icon" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['icon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/><?php endif; ?>
                   <span class="action-label"><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
                  
                </a>
                 <?php if ($this->_tpl_vars['action']['subactions']): ?>
											
					<ul class="action-sub-menu">
						<?php $_from = $this->_tpl_vars['action']['subactions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subname'] => $this->_tpl_vars['subaction']):
?>
							<li id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo $this->_tpl_vars['subaction']['id']; ?>
"
							  <?php if ($this->_tpl_vars['subaction']['name'] == $this->_tpl_vars['selected_action'] || $this->_tpl_vars['subaction']['selected']): ?>class="selected <?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php else: ?>class="plain <?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
<?php endif; ?>
							  <?php if ($this->_tpl_vars['subaction']['subactions']): ?>xf-dropdown<?php endif; ?>"
							  <?php $_from = $this->_tpl_vars['subaction']['atts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subactionAttName'] => $this->_tpl_vars['subactionAttValue']):
?><?php echo ((is_array($_tmp=$this->_tpl_vars['subactionAttName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
="<?php echo ((is_array($_tmp=$this->_tpl_vars['subactionAttValue'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php endforeach; endif; unset($_from); ?>
							  >
								<a class="<?php if ($this->_tpl_vars['subaction']['subactions']): ?>trigger horizontal-trigger<?php endif; ?>" id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
-link" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['subaction']['onclick']): ?> onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['onclick'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
								   accesskey="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['accessKey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-permission="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['permission'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
								   <?php if ($this->_tpl_vars['subaction']['confirm']): ?>data-xf-confirm-message="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['confirm'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
								   >
								   <?php if ($this->_tpl_vars['subaction']['icon']): ?><img id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo $this->_tpl_vars['subaction']['id']; ?>
-icon" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['icon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/><?php endif; ?>
								   <span class="action-label"><?php echo ((is_array($_tmp=$this->_tpl_vars['subaction']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
								   
								</a>
								
								<?php if ($this->_tpl_vars['subaction']['subactions']): ?>
									<ul class="action-sub-sub-menu">
										<?php $_from = $this->_tpl_vars['subaction']['subactions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subsubname'] => $this->_tpl_vars['subsubaction']):
?>
											<li id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
-link" 
												 <?php if ($this->_tpl_vars['subsubaction']['name'] == $this->_tpl_vars['selected_action'] || $this->_tpl_vars['subsubaction']['selected']): ?>class="selected <?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php else: ?>class="plain <?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['class'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
												 <?php $_from = $this->_tpl_vars['subsubaction']['atts']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['subsubactionAttName'] => $this->_tpl_vars['subsubactionAttValue']):
?><?php echo ((is_array($_tmp=$this->_tpl_vars['subsubactionAttName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubactionAttValue'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php endforeach; endif; unset($_from); ?>
												 >
												<a id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
-link" href="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php if ($this->_tpl_vars['subsubaction']['onclick']): ?> onclick="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['onclick'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
												   accesskey="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['accessKey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-permission="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['permission'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"
												   <?php if ($this->_tpl_vars['subsubaction']['confirm']): ?>data-xf-confirm-message="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['confirm'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>
												   >
												   <?php if ($this->_tpl_vars['subsubaction']['icon']): ?><img id="<?php echo $this->_tpl_vars['id_prefix']; ?>
<?php echo $this->_tpl_vars['subsubaction']['id']; ?>
-icon" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['icon'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"/><?php endif; ?>
												   <span class="action-label"><?php echo ((is_array($_tmp=$this->_tpl_vars['subsubaction']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
												   
												</a>
											</li>
										<?php endforeach; endif; unset($_from); ?>
									
									</ul>
								
								<?php endif; ?>
							
						  </li>
						
						<?php endforeach; endif; unset($_from); ?>
					</ul>

			   <?php endif; ?>
                
              </li>
            <?php endif; ?>
            
            
<?php endforeach; endif; unset($_from); ?>
            
     <?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'actions_menu_tail'), $this);?>
       
</ul>
<?php endif; ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>