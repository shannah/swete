<?php /* Smarty version 2.6.18, created on 2013-07-03 00:35:24
         compiled from Dataface_GlanceList.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'if_allowed', 'Dataface_GlanceList.html', 4, false),array('function', 'block', 'Dataface_GlanceList.html', 8, false),array('function', 'record_actions', 'Dataface_GlanceList.html', 14, false),array('function', 'actions_menu', 'Dataface_GlanceList.html', 15, false),)), $this); ?>

<ul class="Dataface_GlanceList">
	<?php $_from = $this->_tpl_vars['records']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['recd']):
?>
		<?php $this->_tag_stack[] = array('if_allowed', array('permission' => 'view','record' => $this->_tpl_vars['recd'])); $_block_repeat=true;$this->_plugins['block']['if_allowed'][0][0]->if_allowed($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<li class="Dataface_GlanceList-item Dataface_GlanceList-item-<?php echo $this->_tpl_vars['recd']->_table->tablename; ?>
">
			
			<span class="Dataface_GlanceList-item-content">
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_oneLineDescription','record' => $this->_tpl_vars['recd']), $this);?>

				<?php echo $this->_tpl_vars['list']->oneLineDescription($this->_tpl_vars['recd']); ?>

				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_oneLineDescription','record' => $this->_tpl_vars['recd']), $this);?>

			</span>
			<span class="Dataface_GlanceList-item-actions">
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_glanceList_actions','record' => $this->_tpl_vars['recd']), $this);?>

				<?php echo $this->_plugins['function']['record_actions'][0][0]->record_actions(array('record' => $this->_tpl_vars['recd']), $this);?>

				<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('record' => $this->_tpl_vars['recd'],'category' => 'glancelistitem_actions'), $this);?>

				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_glancelist_actions','record' => $this->_tpl_vars['recd']), $this);?>

			</span>
		</li>
		<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['if_allowed'][0][0]->if_allowed($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php endforeach; endif; unset($_from); ?>
</ul>