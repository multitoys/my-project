<?php /* Smarty version 2.6.9, created on 2015-10-02 13:55:49
         compiled from backend/currency_edit_form.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'backend/currency_edit_form.html', 1, false),array('modifier', 'translate', 'backend/currency_edit_form.html', 7, false),array('modifier', 'escape', 'backend/currency_edit_form.html', 15, false),array('function', 'html_text', 'backend/currency_edit_form.html', 9, false),)), $this); ?>
	<form class="currency_edit_form" style="display:none;" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post" enctype="multipart/form-data">
	<input name="action" value="save_currency" type="hidden" />
	<input name="CID" type="hidden" value="<?php echo $this->_tpl_vars['_currency']['CID']; ?>
" />
	
	<table cellpadding="4" cellspacing="0">
	<tr>
			<td><?php echo ((is_array($_tmp='curr_currency_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td>
				<?php echo smarty_function_html_text(array('name' => 'Name','values' => $this->_tpl_vars['_currency']), $this);?>

			</td>
	</tr>
	<tr>
			<td><?php echo ((is_array($_tmp='curr_iso3')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td>
				<input name="currency_iso_3" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_currency']['currency_iso_3'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" type="text" size="3" maxlength="3" />
			</td>
	</tr>
	<tr>
			<td><?php echo ((is_array($_tmp='curr_output_format')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td>
				<?php echo smarty_function_html_text(array('name' => 'display_template','values' => $this->_tpl_vars['_currency']), $this);?>

			</td>
	</tr>
	<tr>
			<td><?php echo ((is_array($_tmp='curr_dec_symbol')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>
				<select name="decimal_symbol">
				<?php $_from = $this->_tpl_vars['decimal_symbols']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_symbol']):
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_symbol'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['_currency']['decimal_symbol'] == $this->_tpl_vars['_symbol']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_symbol'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
	</tr>
	<tr>
			<td><?php echo ((is_array($_tmp='curr_dec_placed')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>
				<select name="decimal_places">
				<?php $_from = $this->_tpl_vars['decimal_places']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_place']):
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_place'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['_currency']['decimal_places'] == $this->_tpl_vars['_place']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_place'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
	</tr>
	<tr>
			<td><?php echo ((is_array($_tmp='curr_thousands_delimiter')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td>
				<select name="thousands_delimiter">
				<?php $_from = $this->_tpl_vars['thousands_delimiters']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_name'] => $this->_tpl_vars['_delimiter']):
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_delimiter'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['_currency']['thousands_delimiter'] == $this->_tpl_vars['_delimiter']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
	</tr>
	<tr>
			<td colspan="2">
				<br />
				<img src="images_common/processing.gif" class="processing_image" style="display:none;" />
				<input class="save_currency_handler" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="button" />
				<input class="close_form_handler" value="<?php echo ((is_array($_tmp='btn_cancel')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="button" />
			</td>
	</tr>
	
	</table>
	
	</form>