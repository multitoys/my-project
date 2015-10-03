<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:22
         compiled from backend/catalog_excel_export.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'transcape', 'backend/catalog_excel_export.tpl.html', 3, false),array('modifier', 'translate', 'backend/catalog_excel_export.tpl.html', 7, false),array('modifier', 'escape', 'backend/catalog_excel_export.tpl.html', 31, false),)), $this); ?>

<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</h1>

<?php if ($this->_tpl_vars['excel_export_successful'] == 1): ?>
<p>
<font color=blue class=cat><b><?php echo ((is_array($_tmp='prdexport_csv_msg_successful')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></font><br>

<p>
<a href="get_file.php?getFileParam=<?php echo $this->_tpl_vars['getFileParam']; ?>
"><?php echo ((is_array($_tmp='btn_download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a> (<?php echo $this->_tpl_vars['excel_filesize']; ?>
 Kb)

<?php else: ?>

<p>
<?php if ($this->_tpl_vars['excel_errormsg'] != NULL): ?>
<font color=red><b><?php echo $this->_tpl_vars['excel_errormsg']; ?>
</b></font>
<?php endif; ?>

<form action="" method="post">

<p><?php echo ((is_array($_tmp='prdexport_csv_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

<table>
<tr>
	<td><input type=checkbox name=categ_1></td>
	<td><b><?php echo ((is_array($_tmp='prdcat_category_root')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></td>
</tr>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
<tr>
	<td><input type="checkbox" name="categ_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']][0]; ?>
" checked id="categ_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']][0]; ?>
"></td>
	<td><label for="categ_<?php echo $this->_tpl_vars['categories'][$this->_sections['i']['index']][0]; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['categories'][$this->_sections['i']['index']][1])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</label></td>
</tr>
<?php endfor; endif; ?>
</table>

	<p>
	<table cellpadding=3>
	<tr>
		<td>
		<?php echo ((is_array($_tmp='prdimport_csvl_delimeter')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		</td>
		<td>
			<select name="delimiter">
				<option value=";"><?php echo ((is_array($_tmp='prdimport_csv_delimeter_semicolon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (;)</option>
				<option value=","><?php echo ((is_array($_tmp='prdimport_csvl_delimeter_comma')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (,)</option>
				<option value="\t"><?php echo ((is_array($_tmp='prdimport_csvl_delimeter_tab')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			</select>
		</td>
	</tr>
	<tr>
		<td><?php echo ((is_array($_tmp='prdimport_file_charset')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
		<td>
			<select name="charset">
			<?php $_from = $this->_tpl_vars['charsets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_charset']):
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['default_charset'] == $this->_tpl_vars['_charset']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
			<?php endforeach; endif; unset($_from); ?>
			</select>
		</td>
	</tr>
	</table>

<p>
	<input type=submit value="<?php echo ((is_array($_tmp='prdexport_csv_btn_export')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
	<input type=hidden name=dpt value=catalog>
	<input type=hidden name=sub value=excel_export>
	<input type=hidden name=excel_export value=excel>

</form>

<?php endif; ?>