<?php /* Smarty version 2.6.9, created on 2015-10-02 13:45:45
         compiled from backend/product_options.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/product_options.html', 5, false),array('modifier', 'set_query_html', 'backend/product_options.html', 10, false),array('modifier', 'transcape', 'backend/product_options.html', 18, false),array('modifier', 'set_query', 'backend/product_options.html', 70, false),array('modifier', 'replace', 'backend/product_options.html', 72, false),array('modifier', 'escape', 'backend/product_options.html', 72, false),array('function', 'cycle', 'backend/product_options.html', 22, false),array('function', 'html_text', 'backend/product_options.html', 24, false),)), $this); ?>

<?php if ($this->_tpl_vars['option_name'] == null): ?>

<h1><?php echo ((is_array($_tmp='pgn_product_customparams')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<p><?php echo ((is_array($_tmp='prdopt_page_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<?php echo $this->_tpl_vars['MessageBlock']; ?>

	
	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
	<input name="action" value="save_options" type="hidden" />
	
	<table class="grid">
	<tr class="gridsheader">
		<td><?php echo ((is_array($_tmp='prdopt_custom_option_title')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
		<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
	</tr>
		
	<?php $_from = $this->_tpl_vars['options']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
?>
	<tr class='<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
'>
		<td>
			<?php echo smarty_function_html_text(array('dbfield' => 'name','name' => "extra_option_%lang%_".($this->_tpl_vars['_row']['optionID']),'values' => $this->_tpl_vars['_row'],'table' => @PRODUCT_OPTIONS_TABLE,'style' => "width:250px"), $this);?>

		</td>
		<td align="center">
			<input type="text" name="extra_sort_<?php echo $this->_tpl_vars['_row']['optionID']; ?>
" value="<?php echo $this->_tpl_vars['_row']['sort_order']; ?>
" size="3" />
		</td>
		<td nowrap="nowrap">
			<a href="<?php echo ((is_array($_tmp="&optionID=".($this->_tpl_vars['_row']['optionID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='prdcustopt_value_variants')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<?php echo $this->_tpl_vars['_row']['count_variants']; ?>
)</a>
		</td>
		<td align="center">
			<a href='<?php echo ((is_array($_tmp="action=delete_option&optionID=".($this->_tpl_vars['_row']['optionID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_areyousure')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
'><img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a>
		</td>
	 </tr>
	<?php endforeach; else: ?>
	
	<tr>
		<td colspan="4" align="center"><?php echo ((is_array($_tmp='prdopt_no_product_options')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<?php endif; unset($_from); ?>
		
	<tr class="gridsheader_simple">
		<td colspan="4"><?php echo ((is_array($_tmp='prdopt_add_new_option')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridsheader">
		<td><?php echo ((is_array($_tmp='prdopt_custom_option_title')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
		<td></td>
	</tr>
	<tr>
		<td>
			<?php echo smarty_function_html_text(array('name' => "name_%lang%",'values' => "",'table' => @PRODUCT_OPTIONS_TABLE,'style' => "width:250px"), $this);?>

		</td>
		<td align="center">
			<input type="text" name="add_sort" value="" size="3" />
		</td>
		<td></td>
		<td></td>
		</tr>
	</table>
	
	<p><input type="submit" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></p>
	
	</form>

<?php else: ?>

	<h1 class="breadcrumbs"><a href='<?php echo ((is_array($_tmp="&optionID=")) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
'><?php echo ((is_array($_tmp='pgn_product_customparams')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	&raquo;
	<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='prdcustopt_availablevalues')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '%OPTION_NAME%', $this->_tpl_vars['option_name']) : smarty_modifier_replace($_tmp, '%OPTION_NAME%', $this->_tpl_vars['option_name'])))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</h1>

	<?php echo $this->_tpl_vars['MessageBlock']; ?>


	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
	<input name="action" value="save_values" type="hidden" />
	
	<table class="grid">
	<tr class="gridsheader">
		<td><?php echo ((is_array($_tmp='prdcustopt_value')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	
	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['values']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<tr class="<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
">
		<td>
			<?php echo smarty_function_html_text(array('dbfield' => 'option_value','name' => "option_value_%lang%_".($this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']),'values' => $this->_tpl_vars['values'][$this->_sections['i']['index']],'style' => "width: 250px"), $this);?>

		</td>
		<td align="center">
			<input type="text" name="sort_order_<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']; ?>
" value="<?php echo $this->_tpl_vars['values'][$this->_sections['i']['index']]['sort_order']; ?>
" size="3" />
		</td>
		<td align="center">
			<a href='<?php echo ((is_array($_tmp="&action=delete_variant&variantID=".($this->_tpl_vars['values'][$this->_sections['i']['index']]['variantID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
				<img alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' src="images_common/remove.gif" />
			</a>
		</td>
	</tr>
	
	<?php endfor; else: ?>
	
	 <tr>
		<td colspan="3" align="center"><?php echo ((is_array($_tmp='prdcustopt_option_has_no_values')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</nobr>
		</td>
	 </tr>
	
	<?php endif; ?>
	
	<tr class="gridsheader_simple">
		<td colspan="3"><?php echo ((is_array($_tmp='prdcustopt_value_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridsheader">
		<td><?php echo ((is_array($_tmp='prdcustopt_value')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='str_sort_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td></td>
	</tr>
	
	<tr>
		<td>
			<?php echo smarty_function_html_text(array('name' => 'option_value','values' => '','style' => "width: 250px"), $this);?>

		</td>
		<td align="center">
			<input type="text" name="sort_order" value="" size="3" />
		</td>
		<td>
		</td>
	</tr>
	</table>
	
	<input type="hidden" name="optionID" value="<?php echo $this->_tpl_vars['optionID']; ?>
" />

	<p><input type="submit" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></p>

	</form>

<?php endif; ?>