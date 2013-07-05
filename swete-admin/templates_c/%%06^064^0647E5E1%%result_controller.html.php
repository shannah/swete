<?php /* Smarty version 2.6.18, created on 2013-07-03 00:35:26
         compiled from xataface/RelatedList/result_controller.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'xataface/RelatedList/result_controller.html', 3, false),)), $this); ?>
<div class="resultlist-controller">
	<div class="result-stats related-result-stats">
		<span class="start"><?php echo ((is_array($_tmp=$this->_tpl_vars['now_showing_start'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>-<span class="end"><?php echo ((is_array($_tmp=$this->_tpl_vars['now_showing_finish'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span> of <span class="found"><?php echo ((is_array($_tmp=$this->_tpl_vars['num_related_records'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</span>
		<div class="limit-field">
			<?php echo $this->_tpl_vars['limit_field']; ?>

		</div>
	</div>
	
	<div class="prev-link"><?php echo $this->_tpl_vars['back_link']; ?>
</div>
	<div class="next-link"><?php echo $this->_tpl_vars['next_link']; ?>
</div>
</div>