<?php /* Smarty version 2.6.9, created on 2015-09-22 21:31:15
         compiled from contact_info.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'contact_info.tpl.html', 7, false),array('modifier', 'translate', 'contact_info.tpl.html', 14, false),array('modifier', 'escape', 'contact_info.tpl.html', 15, false),array('function', 'cycle', 'contact_info.tpl.html', 28, false),)), $this); ?>

<?php if ($this->_tpl_vars['error'] != ""): ?>
	<p><b><font color=red><?php echo $this->_tpl_vars['error']; ?>
<br><br></font></b></p>
<?php endif; ?>

<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">

	<table id="reg_fields_tbl" cellpadding="0" cellspacing="0">
	<!-- STRING AUTHORIZATION FIELDS (LOGIN, PASSWORD) -->
	
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
	</tbody>

	<!-- GENERAL INFORMATION (FIRST NAME, LAST NAME, EMAIL ) -->
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td>
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

	<!-- ADDITIONAL FIELDS (SEE  admin.php?dpt=custord&sub=reg_fields URL) -->
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
			<input type='text' name='additional_field_<?php echo ((is_array($_tmp=$this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_ID'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' 
				value='<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['additional_field_values']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
 if ($this->_tpl_vars['additional_field_values'][$this->_sections['j']['index']]['reg_field_ID'] == $this->_tpl_vars['additional_fields'][$this->_sections['i']['index']]['reg_field_ID']):  echo ((is_array($_tmp=$this->_tpl_vars['additional_field_values'][$this->_sections['j']['index']]['reg_field_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif;  endfor; endif; ?>'>
		</td>
	</tr>
	<?php endfor; endif; ?>
	
	<tr class="row_<?php echo smarty_function_cycle(array('values' => 'odd,even'), $this);?>
">
		<td align="right"><input type="checkbox" id="subscribed4news" value="1" name="subscribed4news"<?php if ($this->_tpl_vars['subscribed4news'] == 1): ?> checked="checked"<?php endif; ?> /></td>
		<td><label for="subscribed4news"><?php echo ((is_array($_tmp='usrreg_subscribe_for_blognews')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label></td>
	</tr>
	</table>

	<p><input type="submit" class="my-button" value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' name='save' /></p>
	
</form>