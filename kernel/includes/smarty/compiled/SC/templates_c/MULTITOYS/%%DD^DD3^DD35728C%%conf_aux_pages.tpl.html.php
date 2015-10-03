<?php /* Smarty version 2.6.9, created on 2015-09-29 10:07:47
         compiled from backend/conf_aux_pages.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query', 'backend/conf_aux_pages.tpl.html', 6, false),array('modifier', 'translate', 'backend/conf_aux_pages.tpl.html', 6, false),array('modifier', 'escape', 'backend/conf_aux_pages.tpl.html', 9, false),array('modifier', 'set_query_html', 'backend/conf_aux_pages.tpl.html', 15, false),array('modifier', 'transcape', 'backend/conf_aux_pages.tpl.html', 97, false),array('function', 'html_text', 'backend/conf_aux_pages.tpl.html', 27, false),array('function', 'html_textarea', 'backend/conf_aux_pages.tpl.html', 39, false),)), $this); ?>

<?php if ($this->_tpl_vars['edit'] || $this->_tpl_vars['add_new']): ?>

<h1 class="breadcrumbs">
	<a href='<?php echo ((is_array($_tmp="?did=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
'><?php echo ((is_array($_tmp='pgn_aux_pages')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	&raquo;
	<?php if ($this->_tpl_vars['edit']): ?>
	<?php echo ((is_array($_tmp='infpg_editpage')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['aux_page']['aux_page_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

	<?php else: ?>
	<?php echo ((is_array($_tmp='infpg_addnewpage')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<?php endif; ?> 
</h1>

<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">

	<table border="0" cellspacing="0" cellpadding="5">
	<tr> 
		<td><?php echo ((is_array($_tmp='auxp_lbl_enabled')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
		<td>
			<input type="checkbox" name="aux_page_enabled" value="1" <?php if ($this->_tpl_vars['aux_page']['aux_page_enabled'] || $this->_tpl_vars['aux_page']['aux_page_enabled'] == null): ?>checked<?php endif; ?> />
		</td>
	</tr>
	<tr> 
		<td><?php echo ((is_array($_tmp='infpg_pagename')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
		<td>
			<?php echo smarty_function_html_text(array('name' => 'aux_page_name','values' => $this->_tpl_vars['aux_page'],'size' => 60), $this);?>

		</td>
	</tr>
	<tr> 
		<td><?php echo ((is_array($_tmp='aux_page_slug')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
		<td>
			<input type="text" name="aux_page_slug" value="<?php echo $this->_tpl_vars['aux_page']['aux_page_slug']; ?>
" size="50">
		</td>
	</tr>
	<tr>
		<td colspan="2">
			<?php echo ((is_array($_tmp='infpg_page_text')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br />
			<?php echo smarty_function_html_textarea(array('name' => 'aux_page_text','style' => "width:100%",'rows' => 6,'cols' => 70,'class' => 'mceEditor','values' => $this->_tpl_vars['aux_page']), $this);?>

		</td>
	</tr>
	<tr>
		<td><?php echo ((is_array($_tmp='prdset_meta_keywords')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td>
			<?php echo smarty_function_html_text(array('name' => 'meta_keywords','style' => "width:100%",'values' => $this->_tpl_vars['aux_page']), $this);?>

		</td>
	</tr>
	<tr>
		<td><?php echo ((is_array($_tmp='prdset_meta_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td>
			<?php echo smarty_function_html_textarea(array('name' => 'meta_description','style' => "width:100%",'rows' => 2,'cols' => 35,'values' => $this->_tpl_vars['aux_page']), $this);?>

		</td>
	</tr>
	</table>
	<br />
	<input type="submit" value='<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' name='save' />
	
</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/tiny_mce_config.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>	
<?php else: ?>
	<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

	<p><?php echo ((is_array($_tmp='infpg_pages_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
" class="ajaxform" method="post" enctype="multipart/form-data">
	<input name="action" value="save_order" type="hidden" />
	
	<table cellpadding="0" cellspacing="0" class="grid" id="tbl-aux">
	<tr class="gridsheader"> 
		<td><?php echo ((is_array($_tmp='infpg_pagename')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td><?php echo ((is_array($_tmp='auxp_lbl_enabled')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
	</tr>

	<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['aux_pages']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
	<tbody class="dragable">
	<tr> 
		<td class="handle"><?php echo ((is_array($_tmp=$this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
		<td align="center">
			<input <?php if ($this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_enabled']): ?>checked="checked"<?php endif; ?> type="checkbox" disabled="disabled" />
			<input type="hidden" class="field_priority" name="priority_<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
" value="<?php echo $this->_sections['i']['index']; ?>
" />
		</td>
		<td>
			<a class="bluehref" href='<?php echo ((is_array($_tmp="&edit=".($this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		</td>
		<td>
			<a 	href="javascript:confirmDelete(<?php echo $this->_tpl_vars['aux_pages'][$this->_sections['i']['index']]['aux_page_ID']; ?>
,'<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
','<?php echo ((is_array($_tmp="delete=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
&amp;delete=');">
			<img alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' src="images/remove.gif" border="0" /></a>
		</td>
	</tr>
	</tbody>
	<?php endfor; endif; ?>
	</table>
	</form>

	<input value="<?php echo ((is_array($_tmp='infpg_addnewpage')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" onclick="document.location.href = '<?php echo ((is_array($_tmp='&add_new=yes')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'" type="button" />
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/sortable_table.html", 'smarty_include_vars' => array('table_id' => "tbl-aux")));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>