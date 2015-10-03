<?php /* Smarty version 2.6.9, created on 2015-09-22 13:56:47
         compiled from reg_successful.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'reg_successful.tpl.html', 5, false),array('modifier', 'set_query_html', 'reg_successful.tpl.html', 25, false),)), $this); ?>

<?php if ($this->_tpl_vars['reg_terminated']): ?> 
	<div><?php echo ((is_array($_tmp='usr_successful_account_termination')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>

<?php else: ?>

	<?php if ($this->_tpl_vars['reg_updating']): ?>
		<div><?php echo ((is_array($_tmp='usr_account_update_successful')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
	<?php else: ?>

		<?php if (@CONF_ENABLE_REGCONFIRMATION): ?>
		<h1><?php echo ((is_array($_tmp='usrreg_confirm_acc_activation')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
		<?php else: ?>
		<?php endif; ?>

		<div style="
                font-size: 20px;
                text-align: center;
                padding: 10px;
                color: rgb(0, 51, 102);
                "><?php echo ((is_array($_tmp='usr_msg_registration_successful')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<br><br>
      Доступ к полной информации получите только после того, <br>как с Вами свяжется наш представитель.</div>
		<p style="text-align: center;"><a href="<?php echo ((is_array($_tmp='ukey=office')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" style="
    font-size: large;
    padding: 20px;
    color: coral;
"><?php echo ((is_array($_tmp='lnk_myaccount')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></p>

		<?php if (@CONF_ENABLE_REGCONFIRMATION): ?>
			<form method="get" action="<?php echo ((is_array($_tmp='ukey=act_customer')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" style="text-align:center;">
				<?php echo ((is_array($_tmp='usrreg_confirm_type_code')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
  <input type="text" name="act_code" value="<?php echo $this->_tpl_vars['ActCode']; ?>
" />
				<br />
				<br />
				<input type="submit" value="<?php echo ((is_array($_tmp='btn_activate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			</form>
		<?php endif; ?>
	<?php endif; ?>

<?php endif; ?>