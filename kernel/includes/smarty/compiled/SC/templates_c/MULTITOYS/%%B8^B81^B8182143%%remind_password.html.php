<?php /* Smarty version 2.6.9, created on 2015-09-22 13:22:02
         compiled from remind_password.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'remind_password.html', 1, false),array('modifier', 'set_query_html', 'remind_password.html', 7, false),array('modifier', 'escape', 'remind_password.html', 12, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp='pgn_remind_password')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<?php echo $this->_tpl_vars['MessageBlock__error']; ?>

<?php echo $this->_tpl_vars['MessageBlock__success']; ?>


<?php if (! $this->_tpl_vars['MessageBlock__success']): ?>
<form method="post" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
">
	<input name="action" value="remind" type="hidden" />
	<?php echo ((is_array($_tmp='usr_forgot_password_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	
	<p>
	<input name="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" type="text" />
	<input type="submit" value="<?php echo ((is_array($_tmp='btn_ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
	</p>
	
	<p>
		<a href="<?php echo ((is_array($_tmp='?ukey=register')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='pgn_register')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	</p>
</form>
<?php endif; ?>