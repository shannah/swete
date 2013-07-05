<?php /* Smarty version 2.6.18, created on 2013-07-02 20:11:38
         compiled from Dataface_Details_Controller.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'prev_link', 'Dataface_Details_Controller.html', 26, false),array('function', 'next_link', 'Dataface_Details_Controller.html', 30, false),array('function', 'actions_menu', 'Dataface_Details_Controller.html', 34, false),)), $this); ?>
	<div class="result-stats details-stats">
		<span class="cursor"><?php echo $this->_tpl_vars['ENV']['resultSet']->cursor()+1; ?>
</cursor> of <span class="found"><?php echo $this->_tpl_vars['ENV']['resultSet']->found(); ?>
</span>
			
	</div>

	<div class="prev-link">
		<?php echo $this->_plugins['function']['prev_link'][0][0]->prev_link(array(), $this);?>

	</div>
	
	<div class="next-link">
		<?php echo $this->_plugins['function']['next_link'][0][0]->next_link(array(), $this);?>

	</div>
	
	<div class="record-details-actions">
		<?php echo $this->_plugins['function']['actions_menu'][0][0]->actions_menu(array('id' => "record-details-actions",'id_prefix' => "record-details-actions-",'class' => "icon-only",'category' => 'record_details_actions'), $this);?>

	</div>