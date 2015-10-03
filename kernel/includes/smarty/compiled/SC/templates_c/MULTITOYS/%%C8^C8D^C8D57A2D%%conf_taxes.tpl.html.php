<?php /* Smarty version 2.6.9, created on 2015-10-02 13:55:02
         compiled from backend/conf_taxes.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/conf_taxes.tpl.html', 5, false),array('modifier', 'set_query_html', 'backend/conf_taxes.tpl.html', 11, false),array('modifier', 'transcape', 'backend/conf_taxes.tpl.html', 19, false),array('modifier', 'escape', 'backend/conf_taxes.tpl.html', 25, false),array('modifier', 'set_query', 'backend/conf_taxes.tpl.html', 78, false),array('function', 'cycle', 'backend/conf_taxes.tpl.html', 23, false),)), $this); ?>

<?php if (! $this->_tpl_vars['define_zone_rates'] && ! $this->_tpl_vars['define_rate']): ?>

	<h1><?php echo ((is_array($_tmp='tax_types')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
	
	<p><?php echo ((is_array($_tmp='tax_page_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	
	<?php echo $this->_tpl_vars['MessageBlock']; ?>

	
	<form action='<?php echo ((is_array($_tmp="")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' method="post">
	<input name="action" value="save_classes" type="hidden" />
	
	<table class="grid">
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='tax_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_sales_tax_is_based_on_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
	</tr>

	<?php $_from = $this->_tpl_vars['classes']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
?>
	<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
"> 
		<td>
			<input	name='class_name_<?php echo $this->_tpl_vars['_row']['classID']; ?>
' type="text" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="width:250px;" /> 
		</td>
		<td align="center">
			<select name='tax_based_on_address_<?php echo $this->_tpl_vars['_row']['classID']; ?>
'>
					<option value='0' <?php if ($this->_tpl_vars['_row']['address_type'] == 0): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp='ordr_shipping_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<option value='1' <?php if ($this->_tpl_vars['_row']['address_type'] == 1): ?>selected<?php endif; ?>><?php echo ((is_array($_tmp='ordr_billing_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			</select>
		</td>
		<td>
			<a href='<?php echo ((is_array($_tmp="define_rate=".($this->_tpl_vars['_row']['classID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="normal"><?php echo ((is_array($_tmp='pgn_taxes_define_rates')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		</td>
		<td align="center" valign="middle">
			<a href='<?php echo ((is_array($_tmp="action=delete_class&classID=".($this->_tpl_vars['_row']['classID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
			<img alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' src="images_common/remove.gif" />
			</a>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr>
		<td colspan="4" align="center">&lt; <?php echo ((is_array($_tmp='str_answer_no')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &gt;</td>
	</tr>
	<?php endif; unset($_from); ?>

	<tr class="gridsheader_simple">
		<td colspan="4"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='tax_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_sales_tax_is_based_on_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>
	<tr> 
		<td><input name='new_class_name' type='text' value='' style="width:250px;" /></td>
		<td align="center">
			<select name="new_tax_based_on_address">
				<option value='0'><?php echo ((is_array($_tmp='ordr_shipping_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				<option value='1'><?php echo ((is_array($_tmp='ordr_billing_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			</select>
		</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>
	</table>

	<p><input type='submit' value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' /></p>
	</form>
	<?php endif; ?>

<?php if ($this->_tpl_vars['define_zone_rates']): ?>

	<h1 class="breadcrumbs">
		<a href='<?php echo ((is_array($_tmp="?did=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
'><?php echo ((is_array($_tmp='tax_types')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		&raquo;
		<a href='<?php echo ((is_array($_tmp="&define_rate=".($this->_tpl_vars['define_zone_rates']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp='pgn_taxes_define_rates')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['className']; ?>
</a>
		&raquo;
		<?php echo $this->_tpl_vars['country_name']; ?>

	</h1>

	<?php echo $this->_tpl_vars['MessageBlock']; ?>


	<h2><?php echo ((is_array($_tmp='tax_set_rates_for_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
	<input name="action" value="save_states" type="hidden" />
	
		<table class="grid">
		<tr class="gridsheader">
			<td><?php echo ((is_array($_tmp='rgn_region_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
		</tr>
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['zone_rates']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<?php if ($this->_tpl_vars['zone_rates'][$this->_sections['i']['index']]['zoneID'] != 0): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['zone_rates'][$this->_sections['i']['index']]['zone_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
				
				<?php else: ?>
					<?php if ($this->_tpl_vars['zone_count'] == 0 && $this->_tpl_vars['zone_rate_count'] - 1 == 0):  echo ((is_array($_tmp='rgn_all_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
					<?php if ($this->_tpl_vars['zone_count'] == 0 && $this->_tpl_vars['zone_rate_count'] - 1 != 0):  echo ((is_array($_tmp='rgn_all_other_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
					<?php if ($this->_tpl_vars['zone_count'] != 0 && $this->_tpl_vars['zone_rate_count'] - 1 != 0):  echo ((is_array($_tmp='rgn_other_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
					<?php if ($this->_tpl_vars['zone_count'] != 0 && $this->_tpl_vars['zone_rate_count'] - 1 == 0):  echo ((is_array($_tmp='rgn_all_other_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
				<?php endif; ?>
			</td>
			<td align="center">
				<input type="text" value='<?php echo $this->_tpl_vars['zone_rates'][$this->_sections['i']['index']]['value']; ?>
' name='zone_rate_<?php echo $this->_tpl_vars['zone_rates'][$this->_sections['i']['index']]['zoneID']; ?>
' size="4" />%
			</td>
			<td align="center">
				<a href='<?php echo ((is_array($_tmp="action=delete_state&zoneID=".($this->_tpl_vars['zone_rates'][$this->_sections['i']['index']]['zoneID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' class="confirm_action">
					<img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
				</a>				
			</td>
		</tr>
		<?php endfor; else: ?>
		<tr>
			<td colspan="3" align="center">&lt; <?php echo ((is_array($_tmp='str_answer_no')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &gt;</td>
		</tr>
		<?php endif; ?>
		
		<?php if ($this->_tpl_vars['zone_count'] != 0): ?>
		<tr class="gridsheader_simple">
			<td colspan="3"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		</tr>
		<tr class="gridsheader">
			<td><?php echo ((is_array($_tmp='rgn_region_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<select name='new_zone'>
					<option value='-1'><?php echo ((is_array($_tmp='str_please_select')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<?php if ($this->_tpl_vars['zone_rate_count'] == 0): ?>
					<option value='0'><?php echo ((is_array($_tmp='rgn_all_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<?php endif; ?>
					<?php if ($this->_tpl_vars['zone_rate_count'] != 0 && $this->_tpl_vars['group_exists'] != 1): ?>
					<option value='0'><?php echo ((is_array($_tmp='rgn_all_other_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<?php endif; ?>
					<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['zones']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<option value='<?php echo $this->_tpl_vars['zones'][$this->_sections['i']['index']]['zoneID']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['zones'][$this->_sections['i']['index']]['zone_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
					<?php endfor; endif; ?>
				</select>
			</td>
			<td align="center">
				<input type=text name='new_rate' value='' size="4" />%
			</td>
			<td>&nbsp;</td>
		</tr>
		<?php endif; ?>
		</table>
	
		<p><input type='submit' value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' /></p>
		
	</form>
	
	
	<h2><?php echo ((is_array($_tmp='tax_set_rates_for_zip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>

	<p><?php echo ((is_array($_tmp='tax_set_rates_for_zip_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	<p><?php echo ((is_array($_tmp='tax_set_rates_for_zip_descr2')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
	<input name="action" value="save_zips" type="hidden" />
	
		<table class="grid">
		<tr class="gridsheader">
			<td><?php echo ((is_array($_tmp='usr_custinfo_zip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
		</tr>
	
		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['zip_rates']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<input type=text name='zip_template_<?php echo $this->_tpl_vars['zip_rates'][$this->_sections['i']['index']]['tax_zipID']; ?>
' value='<?php echo $this->_tpl_vars['zip_rates'][$this->_sections['i']['index']]['zip_template']; ?>
' />
			</td>
			<td align="center">
				<input type=text name='zip_rate_<?php echo $this->_tpl_vars['zip_rates'][$this->_sections['i']['index']]['tax_zipID']; ?>
' value='<?php echo $this->_tpl_vars['zip_rates'][$this->_sections['i']['index']]['value']; ?>
' size="4" />%
			</td>
			<td align="center">
				<a href='<?php echo ((is_array($_tmp="action=delete_zip&tax_zipID=".($this->_tpl_vars['zip_rates'][$this->_sections['i']['index']]['tax_zipID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
					<img src="images_common/remove.gif" alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
				</a>				
			</td>
		</tr>
		<?php endfor; else: ?>
		<tr>
			<td colspan="3" align="center">&lt; <?php echo ((is_array($_tmp='str_answer_no')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &gt;</td>
		</tr>
		<?php endif; ?>
	
		<tr class="gridsheader_simple">
			<td colspan="3"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		</tr>
		<tr class="gridsheader">
			<td><?php echo ((is_array($_tmp='usr_custinfo_zip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td>
				<input type=text name='new_zip_template' value='' />
			</td>
			<td align="center">
				<input type=text name='new_zip_rate' value='' size="4" />%
			</td>
			<td>&nbsp;</td>
		</tr>
		</table>

		<p><input type='submit' value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' /></p>
	</form>
<?php endif; ?>

<?php if ($this->_tpl_vars['define_rate']): ?>

	<h1 class="breadcrumbs">
		<a href='<?php echo ((is_array($_tmp="?did=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
'><?php echo ((is_array($_tmp='tax_types')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		&raquo;
		<?php echo ((is_array($_tmp='pgn_taxes_define_rates')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['class_name']; ?>

	</h1>
	
	<p><?php echo ((is_array($_tmp='tax_set_rates_for_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

	<?php echo $this->_tpl_vars['MessageBlock']; ?>


	<form method="post" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
">
	<input name="action" value="save_countries" type="hidden" />

	<table class="grid">
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='usr_custinfo_country')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>

	<?php $_from = $this->_tpl_vars['rates']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
?>
	<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
		<td>
			<?php if ($this->_tpl_vars['_row']['countryID'] != 0): ?>
				<?php echo $this->_tpl_vars['_row']['country']; ?>

			<?php else: ?>
				<?php if ($this->_tpl_vars['country_count'] == 0 && $this->_tpl_vars['rate_count'] - 1 == 0):  echo ((is_array($_tmp='cntr_all_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
				<?php if ($this->_tpl_vars['country_count'] == 0 && $this->_tpl_vars['rate_count'] - 1 != 0):  echo ((is_array($_tmp='cntr_all_other_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
				<?php if ($this->_tpl_vars['country_count'] != 0 && $this->_tpl_vars['rate_count'] - 1 != 0):  echo ((is_array($_tmp='cntr_all_other_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
				<?php if ($this->_tpl_vars['country_count'] != 0 && $this->_tpl_vars['rate_count'] - 1 == 0):  echo ((is_array($_tmp='cntr_all_other_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
			<?php endif; ?>
		</td>
		<td>
			<?php if ($this->_tpl_vars['_row']['countryID'] != 0): ?>
			<div>
				<?php if ($this->_tpl_vars['count_zones'][$this->_tpl_vars['_row']['countryID']] != 0): ?>
					<input type="radio" name='isByZone_<?php echo $this->_tpl_vars['_row']['countryID']; ?>
' <?php if ($this->_tpl_vars['_row']['isByZone'] == '0'): ?>checked<?php endif; ?> value='0' />
				<?php endif; ?>
				<?php echo ((is_array($_tmp='tax_single_overall_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				&nbsp;
				<input type="text" value='<?php echo $this->_tpl_vars['_row']['value']; ?>
' name='rate_<?php echo $this->_tpl_vars['_row']['countryID']; ?>
' size="4" />%
			</div>
			<?php if ($this->_tpl_vars['count_zones'][$this->_tpl_vars['_row']['countryID']] != 0): ?>
			<div>
				<input type=radio name='isByZone_<?php echo $this->_tpl_vars['_row']['countryID']; ?>
' <?php if ($this->_tpl_vars['_row']['isByZone'] == '1'): ?>checked<?php endif; ?> value='1' />
				<?php echo $this->_tpl_vars['admin_is_depended_on_zone'][$this->_tpl_vars['_row']['countryID']]; ?>

				<div><a class="normal" href='<?php echo ((is_array($_tmp="define_rate=&define_zone_rates=".($this->_tpl_vars['_row']['classID'])."&countryID=".($this->_tpl_vars['_row']['countryID']))) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
'><?php echo ((is_array($_tmp='tax_set_rates_for_regions')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
...</a></div>
			</div>
			<?php endif; ?>
			<?php else: ?>
				<?php echo ((is_array($_tmp='tax_single_overall_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<input type=text value='<?php echo $this->_tpl_vars['_row']['value']; ?>
' name='rate_<?php echo $this->_tpl_vars['_row']['countryID']; ?>
' size="4" />%
			<?php endif; ?>
		</td>
		<td align="center">
			<a href='<?php echo ((is_array($_tmp="action=delete_country&countryID=".($this->_tpl_vars['_row']['countryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
				<img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
			</a>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr>
		<td colspan="3" align="center">&lt; <?php echo ((is_array($_tmp='str_answer_no')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &gt;</td>
	</tr>
	<?php endif; unset($_from); ?>
	
	<?php if ($this->_tpl_vars['country_count'] != 0): ?>
	<tr class="gridsheader_simple"> 
		<td colspan="3"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='usr_custinfo_country')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='tax_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>
	<tr>
		<td>
			<select name='new_country'>
				<option value='-1'><?php echo ((is_array($_tmp='str_please_select')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			<?php if ($this->_tpl_vars['rate_count'] == 0): ?>
				<option value='0'><?php echo ((is_array($_tmp='cntr_all_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['rate_count'] != 0 && $this->_tpl_vars['group_exists'] != 1): ?>
				<option value='0'><?php echo ((is_array($_tmp='cntr_all_other_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			<?php endif; ?>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['countries']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<option value='<?php echo $this->_tpl_vars['countries'][$this->_sections['i']['index']]['countryID']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['i']['index']]['country_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
			<?php endfor; endif; ?>
			</select>
		</td>
		<td>
			<input type=text name='new_rate' size="4" value='' />%
		</td>
		<td>&nbsp;</td>
	</tr>
	<?php endif; ?>
	</table>

	<p><input value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" type="submit" /></p>
	
	</form>

<?php endif; ?>