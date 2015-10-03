<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:15
         compiled from backend/custord_custgroup.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/custord_custgroup.tpl.html', 2, false),array('modifier', 'transcape', 'backend/custord_custgroup.tpl.html', 15, false),array('modifier', 'escape', 'backend/custord_custgroup.tpl.html', 24, false),array('modifier', 'set_query_html', 'backend/custord_custgroup.tpl.html', 30, false),array('function', 'cycle', 'backend/custord_custgroup.tpl.html', 19, false),array('function', 'html_text', 'backend/custord_custgroup.tpl.html', 21, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp='pgn_customer_groups')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<form action="" method="post">

<p>
<?php echo ((is_array($_tmp='pgn_customer_groups_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>


<p>
<table class="grid">
<tr class="gridsheader"> 
	<td><?php echo ((is_array($_tmp='str_group')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='usr_custinfo_group_discount')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
</tr>

<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['custgroups']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<?php echo smarty_function_html_text(array('dbfield' => 'custgroup_name','name' => "custgroup_name_%lang%_".($this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['custgroupID']),'values' => $this->_tpl_vars['custgroups'][$this->_sections['i']['index']],'size' => '30'), $this);?>

	</td>
	<td align="center">
		<input size="3" type="text" name='custgroup_discount_<?php echo $this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['custgroupID']; ?>
' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['custgroup_discount'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' />
	</td>
	<td align="center">
		<input size="3" type="text" name='sort_order_<?php echo $this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['custgroupID']; ?>
' value='<?php echo ((is_array($_tmp=$this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['sort_order'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' />	
	</td>
	<td align="center">
		<a href="javascript:confirmDelete(<?php echo $this->_tpl_vars['custgroups'][$this->_sections['i']['index']]['custgroupID']; ?>
,'<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
','<?php echo ((is_array($_tmp="delete=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
&amp;delete=');">
			<img src="images_common/remove.gif" border="0" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
		</a>
	</td>
</tr>
<?php endfor; endif; ?>

<tr class="gridsheader_simple"> 
	<td colspan="4"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
</tr>
<tr class="gridsheader"> 
	<td><?php echo ((is_array($_tmp='str_group')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='usr_custinfo_group_discount')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td></td>
</tr>
<tr bgcolor="white"> 
	<td>
		<?php echo smarty_function_html_text(array('name' => 'custgroup_name','size' => '30'), $this);?>

	</td>
	<td align="center">
		<input name='new_custgroup_discount' type="text" size="3" />
	</td>
	<td align="center">
		<input name='new_sort_order' type="text" size="3" />
	</td>
	<td></td>
</tr>
</table>

<p> 
	<input type="hidden" name="save_custgroups" value="yes" />
	<input name="submit" type="submit" value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
</p>

</form>