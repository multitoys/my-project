<?php /* Smarty version 2.6.9, created on 2015-10-02 13:55:47
         compiled from backend/shipping_methods.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/shipping_methods.html', 1, false),array('modifier', 'set_query', 'backend/shipping_methods.html', 8, false),array('modifier', 'set_query_html', 'backend/shipping_methods.html', 29, false),array('modifier', 'transcape', 'backend/shipping_methods.html', 32, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp='shp_shipping_types')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<p><?php echo ((is_array($_tmp='shp_page_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<?php if ($this->_tpl_vars['shipping_methods']): ?>
<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
" class="ajaxform" method="post" enctype="multipart/form-data">
<input name="action" value="save_order" type="hidden" />

<table id="tbl-methods" class="grid">
<tr class="gridsheader">
	<td><?php echo ((is_array($_tmp='enabled')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td><?php echo ((is_array($_tmp='str_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td>&nbsp;</td>
	<td><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
</tr>
<?php $_from = $this->_tpl_vars['shipping_methods']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['_fe'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['_fe']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_method']):
        $this->_foreach['_fe']['iteration']++;
?>
<tbody class="dragable">
<tr>
	<td class="handle" align="center">
		<input <?php if ($this->_tpl_vars['_method']['Enabled']): ?>checked="checked"<?php endif; ?> type="checkbox" disabled="disabled" />
	</td>
	<td class="handle">
	<?php echo $this->_tpl_vars['_method']['Name']; ?>

	<input type="hidden" class="field_priority" name="priority_<?php echo $this->_tpl_vars['_method']['SID']; ?>
" value="<?php echo ($this->_foreach['_fe']['iteration']-1); ?>
" />
	</td>
	<td>
		<a href='<?php echo ((is_array($_tmp="action=edit_method&sid=".($this->_tpl_vars['_method']['SID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	</td>
	<td align="center">
		<a href='<?php echo ((is_array($_tmp="action=delete_method&sid=".($this->_tpl_vars['_method']['SID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title="<?php echo ((is_array($_tmp='cnfrm_areyousure')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
"><img align="middle" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" src="images_common/remove.gif" /></a>
	</td>
</tr>
</tbody>
<?php endforeach; endif; unset($_from); ?>
</table>

</form>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/sortable_table.html", 'smarty_include_vars' => array('table_id' => "tbl-methods")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

<?php endif; ?>

<input value="<?php echo ((is_array($_tmp='shp_add_method')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" onclick="document.location.href='<?php echo ((is_array($_tmp="action=add_method")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'" type="button" />