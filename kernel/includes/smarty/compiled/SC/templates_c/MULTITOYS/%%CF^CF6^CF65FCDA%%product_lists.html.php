<?php /* Smarty version 2.6.9, created on 2015-10-02 13:45:46
         compiled from backend/product_lists.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/product_lists.html', 1, false),array('modifier', 'set_query_html', 'backend/product_lists.html', 7, false),array('modifier', 'escape', 'backend/product_lists.html', 20, false),array('modifier', 'replace', 'backend/product_lists.html', 26, false),array('modifier', 'transcape', 'backend/product_lists.html', 29, false),array('function', 'cycle', 'backend/product_lists.html', 18, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<p><?php echo ((is_array($_tmp='prdlist_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
<input name="action" value="save_lists" type="hidden" />

<table id="tbl-methods" class="grid">
<tr class="gridsheader">
	<td><?php echo ((is_array($_tmp='prdlist_id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='str_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='prdlist_products_in_list')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
</tr>
<?php $_from = $this->_tpl_vars['product_lists']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_list']):
?>
<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
	<td>
		<strong><?php echo ((is_array($_tmp=$this->_tpl_vars['_list']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</strong>
	</td>
	<td>
		<input name="name_<?php echo ((is_array($_tmp=$this->_tpl_vars['_list']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_list']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="20" maxlength="255" />
	</td>
	<td align="center">
		<a class="normal" href='<?php echo ((is_array($_tmp="action=edit_list&list_id=".($this->_tpl_vars['_list']['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp='prdlist_lbl_products_in_list')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '%PRODUCTS_NUM%', $this->_tpl_vars['_list']['products_num']) : smarty_modifier_replace($_tmp, '%PRODUCTS_NUM%', $this->_tpl_vars['_list']['products_num'])); ?>
</a>
	</td>
	<td align="center">
		<a href='<?php echo ((is_array($_tmp="action=delete_list&list_id=".($this->_tpl_vars['_list']['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title="<?php echo ((is_array($_tmp='cnfrm_areyousure')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
"><img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" border="0" /></a>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>
<tr class="gridsheader_simple">
	<td colspan="4"><?php echo ((is_array($_tmp='prdlist_add_list_title')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
</tr>
<tr class="gridsheader">
	<td><?php echo ((is_array($_tmp='prdlist_id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='str_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td></td>
	<td></td>
</tr>
<tr>
	<td>
		<input name="id" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['new_list']['id'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" type="text" size="20" maxlength="20" />
	</td>
	<td>
		<input name="name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['new_list']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="20" maxlength="255" />
	</td>
	<td>
	</td>
	<td>
	</td>
</tr>
</table>

<p>
<input value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" type="submit" />
</p>
</form>