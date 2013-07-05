<?php /* Smarty version 2.6.18, created on 2013-07-02 20:07:26
         compiled from Dataface_Login_Prompt.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'use_macro', 'Dataface_Login_Prompt.html', 20, false),array('block', 'fill_slot', 'Dataface_Login_Prompt.html', 21, false),array('block', 'translate', 'Dataface_Login_Prompt.html', 39, false),array('block', 'define_slot', 'Dataface_Login_Prompt.html', 50, false),array('function', 'block', 'Dataface_Login_Prompt.html', 49, false),array('function', 'actions', 'Dataface_Login_Prompt.html', 116, false),array('modifier', 'nl2br', 'Dataface_Login_Prompt.html', 58, false),array('modifier', 'escape', 'Dataface_Login_Prompt.html', 95, false),)), $this); ?>
<?php $this->_tag_stack[] = array('use_macro', array('file' => "Dataface_Main_Template.html")); $_block_repeat=true;$this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'custom_stylesheets')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
	<style type="text/css">
	<?php echo '
		#Login-Username label, #Login-Password label {
			display: block;
			float: left;
			width: 100px;
			text-align: right;
			padding-right: 1em;
		}
	'; ?>

	</style>
	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
	<?php $this->_tag_stack[] = array('fill_slot', array('name' => 'html_body')); $_block_repeat=true;$this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
		<div class="login-prompt-wrapper">
			
		
			<?php if ($this->_tpl_vars['ENV']['user']): ?>
			<div id="xf-already-logged-in-msg" style="display:none"><?php $this->_tag_stack[] = array('translate', array('id' => 'You are already logged in')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>You are already logged in<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></div>
			<script>
				<?php echo '
					alert(document.getElementById(\'xf-already-logged-in-msg\').innerHTML);
					var url = window.location.href;
					url = url.replace(/-action=login_prompt/, \'\');
					window.location.href=url;
				'; ?>

			</script>
			<?php endif; ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_login_form'), $this);?>

			<?php $this->_tag_stack[] = array('define_slot', array('name' => 'login_form')); $_block_repeat=true;$this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>
			<h1><?php $this->_tag_stack[] = array('translate', array('id' => 'Please Login')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Please Login to access this section of the site<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></h1>
			<?php if ($this->_tpl_vars['ENV']['APPLICATION_OBJECT']->numMessages() > 0): ?>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_message'), $this);?>

				<div class="portalMessage">
					<ul>
					<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'message'), $this);?>

					<?php $_from = $this->_tpl_vars['ENV']['APPLICATION_OBJECT']->getMessages(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['msg2']):
?>
						<li><?php echo ((is_array($_tmp=$this->_tpl_vars['msg2'])) ? $this->_run_mod_handler('nl2br', true, $_tmp) : smarty_modifier_nl2br($_tmp)); ?>
</li>
					<?php endforeach; endif; unset($_from); ?>
					</ul>
				</div>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_message'), $this);?>

			<?php endif; ?>

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
			
			<?php if ($this->_tpl_vars['msg']): ?><div class="portalMessage"><?php echo $this->_tpl_vars['msg']; ?>
</div><?php endif; ?>
			<form action="<?php echo $this->_tpl_vars['ENV']['DATAFACE_SITE_HREF']; ?>
" method="post" class="xataface-login-form">
			<input type="hidden" name="-action" value="login" />
			<?php if (! $this->_tpl_vars['redirect']): ?>
				<?php $this->assign('rkey1', "-redirect"); ?>
				<?php $this->assign('rkey2', "-redirect"); ?>
				<?php if ($this->_tpl_vars['ENV']['query'][$this->_tpl_vars['rkey1']]): ?>
					<?php $this->assign('redirect', $this->_tpl_vars['ENV']['query'][$this->_tpl_vars['rkey1']]); ?>
				<?php elseif ($this->_tpl_vars['ENV']['query'][$this->_tpl_vars['rkey2']]): ?>
					<?php $this->assign('redirect', $this->_tpl_vars['ENV']['query'][$this->_tpl_vars['rkey2']]); ?>
				<?php elseif ($_SESSION[$this->_tpl_vars['rkey1']]): ?>
					<?php $this->assign('redirect', $_SESSION[$this->_tpl_vars['rkey1']]); ?>
				<?php elseif ($_SESSION[$this->_tpl_vars['rkey2']]): ?>
					<?php $this->assign('redirect', $_SESSION[$this->_tpl_vars['rkey2']]); ?>
				<?php endif; ?>
			<?php endif; ?>
			<input type="hidden" name="-redirect" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['redirect'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
" />
			<fieldset>
			<legend><?php $this->_tag_stack[] = array('translate', array('id' => 'Login Form')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login Form<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?></legend>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'before_login_username'), $this);?>

				<div id="Login-Username">
					<label><?php $this->_tag_stack[] = array('translate', array('id' => 'Username')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Username<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</label>
					<input type="text" name="UserName" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['REQUEST']['UserName'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
				</div>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_login_username'), $this);?>

				<div id="Login-Password">
					<label><?php $this->_tag_stack[] = array('translate', array('id' => 'Password')); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Password<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>:</label>
					<input type="password" name="Password" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['ENV']['REQUEST']['Password'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
">
				</div>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_login_password'), $this);?>

				<div id="Login-submit-panel">
					<input id="Login-submit" name="-submit" type="submit" value="<?php $this->_tag_stack[] = array('translate', array('id' => "scripts.GLOBAL.LABEL_LOGIN_SUBMIT")); $_block_repeat=true;$this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat);while ($_block_repeat) { ob_start(); ?>Login<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['translate'][0][0]->translate($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>"/>
				</div>
				<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_login_submit'), $this);?>

			</fieldset>
			
			</form>
			<?php echo $this->_plugins['function']['actions'][0][0]->actions(array('category' => 'login_actions','var' => 'login_actions'), $this);?>

			<ul>
			<?php $_from = $this->_tpl_vars['login_actions']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['action']):
?>
				<li><a href="<?php echo ((is_array($_tmp=$this->_tpl_vars['action']['url'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['action']['label'])) ? $this->_run_mod_handler('escape', true, $_tmp) : smarty_modifier_escape($_tmp)); ?>
</a></li>
			<?php endforeach; endif; unset($_from); ?>
			</ul>
			<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['define_slot'][0][0]->define_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
			<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_login_form'), $this);?>

		
		</div>
		<?php echo $this->_plugins['function']['block'][0][0]->block(array('name' => 'after_global_footer'), $this);?>

	<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['fill_slot'][0][0]->fill_slot($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>
<?php $_block_content = ob_get_contents(); ob_end_clean(); $_block_repeat=false;echo $this->_plugins['block']['use_macro'][0][0]->use_macro($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat); }  array_pop($this->_tag_stack); ?>