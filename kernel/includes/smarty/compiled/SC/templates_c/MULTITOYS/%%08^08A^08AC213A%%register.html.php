<?php /* Smarty version 2.6.9, created on 2015-09-21 23:56:14
         compiled from register.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'register.html', 2, false),array('modifier', 'replace', 'register.html', 12, false),array('modifier', 'set_query_html', 'register.html', 23, false),array('modifier', 'escape', 'register.html', 31, false),array('function', 'cycle', 'register.html', 44, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp='usrreg_registration_form')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<?php if ($this->_tpl_vars['order'] != NULL): ?>
	<p><?php echo ((is_array($_tmp='checkout_continue_tip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<?php endif; ?>

	<?php if ($this->_tpl_vars['overflowCustomers']): ?>
	<div class="error_msg_f"><?php echo ((is_array($_tmp=@TRIAL_STRING_CUSTOMERS_OVERFLOW)) ? $this->_run_mod_handler('replace', true, $_tmp, '[NUM]', @TRIAL_MAX_CUSTOMERS_NUM) : smarty_modifier_replace($_tmp, '[NUM]', @TRIAL_MAX_CUSTOMERS_NUM)); ?>
</div>
	<p><?php echo @TRIAL_STRING_LIMITATIONS; ?>
</p>
	<?php endif; ?>

<p><?php echo ((is_array($_tmp='usrreg_required_regform_fields_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>


<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post" name="RegisterForm"><div class="form_wrapper">

	<table id="reg_fields_tbl" cellpadding="0" cellspacing="0">
		
	<tbody id="reg_auth_fields">
	<tr>
		<td><span class="asterisk">*</span><?php echo ((is_array($_tmp='usr_custinfo_login')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><input type="text" name="login" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	</tr>
	<tr>
		<td><span class="asterisk">*</span><?php echo ((is_array($_tmp='usr_custinfo_password')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><input type="password" name="cust_password1" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cust_password1'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	</tr>
	<tr>
		<td><span class="asterisk">*</span><?php echo ((is_array($_tmp='usrreg_customer_confirm_password')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><input type="password" name="cust_password2" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['cust_password2'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	</tr>
		
		<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td>
			<?php if ($this->_tpl_vars['SessionRefererLogin'] || @CONF_AFFILIATE_PROGRAM_ENABLED == 0): ?>
			<input name="affiliationLogin" type="hidden" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['SessionRefererLogin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" />
			<?php endif; ?>
			<span class="asterisk">*</span><?php echo ((is_array($_tmp='usr_custinfo_first_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		</td>
		<td><input type="text" name="first_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	</tr>
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td><span class="asterisk">*</span><?php echo ((is_array($_tmp='usr_custinfo_last_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><input type="text" name="last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /></td>
	</tr>
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td><span class="asterisk">*</span><?php echo ((is_array($_tmp='usr_custinfo_email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><input type="text" name="email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
"></td>
	</tr>

		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['additional_fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<?php $this->assign('__key', "additional_field_".($this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_ID'])); ?>
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td>
			<?php if ($this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_required']): ?><span class="asterisk">*</span><?php endif; ?>
			<?php echo ((is_array($_tmp=$this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

		</td>
		<td>
			<input type='text' name='additional_field_<?php echo $this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_ID']; ?>
' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['post_data'][$this->_tpl_vars['__key']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' />
		</td>
	</tr>
	<?php endfor; endif; ?>
	
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td align="right"><input type="checkbox" id="subscribed4news" value="1" name="subscribed4news"<?php if ($this->_tpl_vars['subscribed4news'] == 1): ?> checked="checked"<?php endif; ?> /></td>
		<td><label for="subscribed4news"><?php echo ((is_array($_tmp='usrreg_subscribe_for_blognews')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label></td>
	</tr> 

		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "address_form.html", 'smarty_include_vars' => array('name_space' => 'address','address' => $this->_tpl_vars['address'],'intable' => 0,'asknames' => 0,'form_name' => 'RegisterForm','ukey' => 'register')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<input type="hidden" name="action" value="save">
	<?php if (@CONF_ENABLE_CONFIRMATION_CODE): ?>
		
	<tr><td colspan="2">&nbsp;</td></tr>
	
		<tr>
		<td colspan="2"><?php echo ((is_array($_tmp='str_enter_ccode')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr>
		<td align="right">
			<img src="<?php echo @URL_ROOT; ?>
/imgval.php" alt="code" border="0" />
		</td>
		<td align="left">
			<input name="fConfirmationCode" value="" type="text" />
		</td>
	</tr>
	</tbody>
	<?php endif; ?>
    <?php if (! $this->_tpl_vars['SessionRefererLogin'] && @CONF_AFFILIATE_PROGRAM_ENABLED == 1): ?>
    <tr><td colspan="2">&nbsp;</td></tr>
    <tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
        <td style="font-size: 90%;"><?php echo ((is_array($_tmp='usr_custinfo_referrer')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
        <td><input type="text" name="affiliationLogin" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['affiliationLogin'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="width: 100px;" /></td>
    </tr>
    <?php endif; ?>
</table>


<p><input type="submit" id="reg_submit_btn" class="blue-button register" value="<?php echo ((is_array($_tmp='btn_ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" name="save" /></p>
</div>
</form>


<?php if ($this->_tpl_vars['reg_updating'] && $this->_tpl_vars['log'] != @ADMIN_LOGIN): ?>
<p>[ <a class=bold href="javascript:confirmUnsubscribe();"><?php echo ((is_array($_tmp='lnk_terminate_account')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a> ]</p>
<?php endif; ?>