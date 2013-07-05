<?php /* Smarty version 2.6.18, created on 2013-07-04 20:54:14
         compiled from xataface/modules/g2/advanced_find_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'xataface/modules/g2/advanced_find_form.html', 19, false),array('modifier', 'replace', 'xataface/modules/g2/advanced_find_form.html', 21, false),)), $this); ?>
<fieldset class="xf-advanced-find-form">
	<legend>Advanced Search</legend>

	<table class="xf-advanced-find-form-table">
		<tr>
		<?php $this->assign('counter', 1); ?>
		<?php $_from = $this->_tpl_vars['fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fkey'] => $this->_tpl_vars['field']):
?>
			<?php if ($this->_tpl_vars['field']['find']['label']): ?>
				<?php $this->assign('label', $this->_tpl_vars['field']['find']['label']); ?>
			<?php else: ?>
				<?php $this->assign('label', $this->_tpl_vars['field']['widget']['label']); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['field']['find']['type']): ?>
				<?php $this->assign('widgetType', $this->_tpl_vars['field']['find']['type']); ?>
			<?php else: ?>
				<?php $this->assign('widgetType', $this->_tpl_vars['field']['widget']['type']); ?>
			<?php endif; ?>
			
			<td><label><?php echo ((is_array($_tmp=$this->_tpl_vars['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>
				<?php if ($this->_tpl_vars['field']['vocabulary']): ?>
					<select name="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '/') : smarty_modifier_replace($_tmp, '.', '/')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="xf-advanced-find-field xf-advanced-find--<?php echo $this->_tpl_vars['fkey']; ?>
" data-xf-vocabulary="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['vocabulary'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['widgetType'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['field']['find']['type']): ?>data-xf-find-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['find']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>>
					
					</select>
				<?php else: ?>
					<input type="text" name="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '/') : smarty_modifier_replace($_tmp, '.', '/')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="xf-advanced-find-field xf-advanced-find--<?php echo ((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['widgetType'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['field']['find']['type']): ?>data-xf-find-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['find']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>/>
				<?php endif; ?>
				<div class="formHelp"><?php echo ((is_array($_tmp=$this->_tpl_vars['field']['find']['description'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</div>
			</td>
			<?php if ($this->_tpl_vars['counter'] == 2): ?>
				</tr>
				<tr>
				<?php $this->assign('counter', 0); ?>
			<?php endif; ?>
			<?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?>
		<?php endforeach; endif; unset($_from); ?>
		<?php if ($this->_tpl_vars['counter'] == 2): ?>
			</tr>
		<?php endif; ?>
	
	</table>
	
	<?php $_from = $this->_tpl_vars['relatedFields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['relationshipLabel'] => $this->_tpl_vars['rfields']):
?>
		<div class="xf-related-find-form-panel">
			
			<h3><?php echo ((is_array($_tmp=$this->_tpl_vars['relationshipLabel'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</h3>
			
			<table class="xf-advanced-find-form-table">
				<tr>
				<?php $this->assign('counter', 1); ?>
				<?php $_from = $this->_tpl_vars['rfields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['fkey'] => $this->_tpl_vars['field']):
?>
					<?php if ($this->_tpl_vars['field']['find']['label']): ?>
						<?php $this->assign('label', $this->_tpl_vars['field']['find']['label']); ?>
					<?php else: ?>
						<?php $this->assign('label', $this->_tpl_vars['field']['widget']['label']); ?>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['field']['find']['type']): ?>
						<?php $this->assign('widgetType', $this->_tpl_vars['field']['find']['type']); ?>
					<?php else: ?>
						<?php $this->assign('widgetType', $this->_tpl_vars['field']['widget']['type']); ?>
					<?php endif; ?>
							<td><label><?php echo ((is_array($_tmp=$this->_tpl_vars['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</label>
						<?php if ($this->_tpl_vars['field']['vocabulary']): ?>
							<select name="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '/') : smarty_modifier_replace($_tmp, '.', '/')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="xf-advanced-find-field xf-advanced-find--<?php echo $this->_tpl_vars['fkey']; ?>
" data-xf-vocabulary="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['vocabulary'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['widgetType'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['field']['find']['type']): ?>data-xf-find-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['find']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>>
							
							</select>
						<?php else: ?>
							<input type="text" name="<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('replace', true, $_tmp, '.', '/') : smarty_modifier_replace($_tmp, '.', '/')))) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" class="xf-advanced-find-field xf-advanced-find--<?php echo ((is_array($_tmp=$this->_tpl_vars['fkey'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" data-xf-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['widgetType'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" <?php if ($this->_tpl_vars['field']['find']['type']): ?>data-xf-find-widget-type="<?php echo ((is_array($_tmp=$this->_tpl_vars['field']['find']['type'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"<?php endif; ?>/>
						<?php endif; ?>
					</td>
					<?php if ($this->_tpl_vars['counter'] == 2): ?>
						</tr>
						<tr>
						<?php $this->assign('counter', 0); ?>
					<?php endif; ?>
					<?php $this->assign('counter', $this->_tpl_vars['counter']+1); ?>
				<?php endforeach; endif; unset($_from); ?>
				<?php if ($this->_tpl_vars['counter'] == 2): ?>
					</tr>
				<?php endif; ?>
			
			</table>
		
		</div>
		
	<?php endforeach; endif; unset($_from); ?>
	
	<div class="xf-advanced-find-buttons">
	
		<button class="xf-advanced-find-clear">Clear</button>
		<button class="xf-advanced-find-search">Search</button>
	</div>

</fieldset>