<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:47
         compiled from backend/custord_reg_fields.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/custord_reg_fields.tpl.html', 2, false),array('modifier', 'set_query_html', 'backend/custord_reg_fields.tpl.html', 3, false),array('modifier', 'transcape', 'backend/custord_reg_fields.tpl.html', 17, false),array('modifier', 'escape', 'backend/custord_reg_fields.tpl.html', 28, false),array('function', 'cycle', 'backend/custord_reg_fields.tpl.html', 20, false),array('function', 'html_text', 'backend/custord_reg_fields.tpl.html', 22, false),)), $this); ?>
<?php echo $this->_tpl_vars['MessageBlock']; ?>

<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
<input type="hidden" name="save_fields" value="yes" />

	<p>
	<?php echo ((is_array($_tmp='usr_custinfo_fields_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	</p>

	<h2><?php echo ((is_array($_tmp='regform_customfields_configurator')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
	
	<table class="grid">
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='usr_custinfo_custom_field_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='usr_custinfo_custom_field_required')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
	</tr>
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['fields']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
"> 
		<td> 
			<?php echo smarty_function_html_text(array('dbfield' => 'reg_field_name','name' => "reg_field_name_%lang%_".($this->_tpl_vars['fields'][$this->_sections['i']['index']]['reg_field_ID']),'values' => $this->_tpl_vars['fields'][$this->_sections['i']['index']],'style' => "width: 250px;"), $this);?>

		</td>
		<td align="center"> 
			<input name='reg_field_required_<?php echo $this->_tpl_vars['fields'][$this->_sections['i']['index']]['reg_field_ID']; ?>
' type="checkbox" value='1'<?php if ($this->_tpl_vars['fields'][$this->_sections['i']['index']]['reg_field_required'] == 1): ?> checked<?php endif; ?> />
		</td>
		<td>
			<input name='sort_order_<?php echo $this->_tpl_vars['fields'][$this->_sections['i']['index']]['reg_field_ID']; ?>
' type="text" value='<?php echo ((is_array($_tmp=$this->_tpl_vars['fields'][$this->_sections['i']['index']]['sort_order'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' size="3" />
		</td>
		<td align="center">
			<a href='<?php echo ((is_array($_tmp="delete=".($this->_tpl_vars['fields'][$this->_sections['i']['index']]['reg_field_ID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
				<img src="images_common/remove.gif" alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
			</a>
		</td>
	</tr>
	<?php endfor; endif; ?>

	<tr class="gridsheader_simple">
		<td colspan="4"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='usr_custinfo_custom_field_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='usr_custinfo_custom_field_required')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>
	<tr> 
		<td>
			<?php echo smarty_function_html_text(array('name' => 'reg_field_name','style' => "width: 250px;"), $this);?>

		</td>
		<td align="center"> 
			<input name='reg_field_required' type='checkbox' value='1' />
		</td>
		<td>
			<input  type="text" name='sort_order' size="3" />
		</td>
		<td></td>
	</tr>
	</table>

	<p> 
		<input name="submit" type="submit" value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
	</p>

</form>


<h2><?php echo ((is_array($_tmp='regform_address_configurator')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
<input type="hidden" name="save_address_form" value="yes" />

	<table class="grid">
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='usr_custinfo_custom_field_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td align="center"><?php echo ((is_array($_tmp='usr_custinfo_custom_field_required')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td align="center"><?php echo ((is_array($_tmp='regform_optional_field')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td align="center"><?php echo ((is_array($_tmp='regform_not_requested')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridline1"> 
		<td><b><?php echo ((is_array($_tmp='str_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
		<td align="center"><input type="radio" name="addr_address" value="0" <?php if (@CONF_ADDRESSFORM_ADDRESS == 0): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_address" value="1" <?php if (@CONF_ADDRESSFORM_ADDRESS == 1): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_address" value="2" <?php if (@CONF_ADDRESSFORM_ADDRESS == 2): ?> checked<?php endif; ?> /></td>
	</tr>
	<tr  class="gridline"> 
		<td><b><?php echo ((is_array($_tmp='usr_custinfo_city')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
		<td align="center"><input type="radio" name="addr_city" value="0" <?php if (@CONF_ADDRESSFORM_CITY == 0): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_city" value="1" <?php if (@CONF_ADDRESSFORM_CITY == 1): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_city" value="2" <?php if (@CONF_ADDRESSFORM_CITY == 2): ?> checked<?php endif; ?> /></td>
	</tr>
	<tr class="gridline1"> 
		<td><b><?php echo ((is_array($_tmp='usr_custinfo_state')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
		<td align="center"><input type="radio" name="addr_state" value="0" <?php if (@CONF_ADDRESSFORM_STATE == 0): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_state" value="1" <?php if (@CONF_ADDRESSFORM_STATE == 1): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_state" value="2" <?php if (@CONF_ADDRESSFORM_STATE == 2): ?> checked<?php endif; ?> /></td>
	</tr>
	<tr  class="gridline"> 
		<td><b><?php echo ((is_array($_tmp='usr_custinfo_zip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
		<td align="center"><input type="radio" name="addr_zip" value="0" <?php if (@CONF_ADDRESSFORM_ZIP == 0): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_zip" value="1" <?php if (@CONF_ADDRESSFORM_ZIP == 1): ?> checked<?php endif; ?> /></td>
		<td align="center"><input type="radio" name="addr_zip" value="2" <?php if (@CONF_ADDRESSFORM_ZIP == 2): ?> checked<?php endif; ?> /></td>
	</tr>
	<tr class="gridline1"> 
		<td><b><?php echo ((is_array($_tmp='usr_custinfo_country')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
		<td align="center" colspan="3"><?php echo ((is_array($_tmp='regform_address_configurator_country_hint')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	</table>

	<p><input name="submit" type="submit" value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' /></p>
</form>