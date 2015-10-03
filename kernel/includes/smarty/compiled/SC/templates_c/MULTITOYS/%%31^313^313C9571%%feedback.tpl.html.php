<?php /* Smarty version 2.6.9, created on 2015-09-24 06:57:53
         compiled from feedback.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'feedback.tpl.html', 3, false),array('modifier', 'set_query_html', 'feedback.tpl.html', 11, false),array('modifier', 'escape', 'feedback.tpl.html', 14, false),)), $this); ?>

<h1><?php echo ((is_array($_tmp='pgn_feedback')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<?php if ($this->_tpl_vars['sent'] == NULL): ?>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<p><?php echo ((is_array($_tmp='feedback_description_general')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

<form name="form1" method="post" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
">
<div class="common_wrapper">
	<?php echo ((is_array($_tmp='str_your_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br>
	<input name="customer_name" type="text" size="40" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['feedback_data']['customer_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><br>

	<?php echo ((is_array($_tmp='usr_custinfo_email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
<br>
	<input name="customer_email" type="text" size="40" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['feedback_data']['customer_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><br>

	<?php echo ((is_array($_tmp='str_subject')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br>
	<input name="message_subject" type="text" size="40" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['feedback_data']['message_subject'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"><br>

	<?php echo ((is_array($_tmp='feedback_message_text')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br>
	<textarea name="message_text" cols="60" rows="7"><?php echo ((is_array($_tmp=$this->_tpl_vars['feedback_data']['message_text'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</textarea>

	<?php if (@CONF_ENABLE_CONFIRMATION_CODE): ?>
	<br />
	<br />
	<table cellpadding="6" cellspacing="0">
	<tr class="background1">
		<td colspan="2"><?php echo ((is_array($_tmp='str_enter_ccode')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="background1">
		<td align="right"><img src="<?php echo @URL_ROOT; ?>
/imgval.php" alt="code" align="right" /></td>
		<td>
			<input name="fConfirmationCode" value="" type="text" style="width:200px;" />
		</td>
	</tr>
	</table>
	<?php endif; ?>

	<p>
	<input type="submit" value="OK">
	<input type="hidden" name="send" value="yes">
	<input type="hidden" name="feedback" value="yes">
	</p>
</div>
</form>

<?php else: ?>

<p><b><font class=faq color=blue><?php echo ((is_array($_tmp='feedback_msg_sent_successfully')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</font></b></p>

<?php endif; ?>