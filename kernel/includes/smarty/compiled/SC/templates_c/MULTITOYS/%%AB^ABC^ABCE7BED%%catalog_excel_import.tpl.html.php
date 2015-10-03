<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:20
         compiled from backend/catalog_excel_import.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'transcape', 'backend/catalog_excel_import.tpl.html', 3, false),array('modifier', 'translate', 'backend/catalog_excel_import.tpl.html', 3, false),array('modifier', 'set_query_html', 'backend/catalog_excel_import.tpl.html', 35, false),array('modifier', 'escape', 'backend/catalog_excel_import.tpl.html', 99, false),)), $this); ?>
<?php if ($this->_tpl_vars['excel_import_result'] == 'ok'): ?>
<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
&nbsp;&raquo;&nbsp;<?php echo ((is_array($_tmp='prdimport_step')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
&nbsp;2/2</h1>
<div class="success_block" ><span class="success_message"><?php echo ((is_array($_tmp='msg_update_successful')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></div>
<?php echo $this->_tpl_vars['limit_msg']; ?>

<h2 style="margin-left:10px;"><?php echo ((is_array($_tmp='lbl_prdimport_report')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2>
<ul style="list-style-type:none">
	<li><?php echo ((is_array($_tmp='lbl_product_added')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo $this->_tpl_vars['product_added']; ?>
</b></li>
	<li><?php echo ((is_array($_tmp='lbl_product_modify')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo $this->_tpl_vars['product_modify']; ?>
</b></li>
	<li><?php echo ((is_array($_tmp='lbl_category_added')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo $this->_tpl_vars['category_added']; ?>
</b></li>
</ul>
<?php else: ?>

	<?php if ($this->_tpl_vars['excel_import_configurator'] != NULL): ?>

	<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>

	&raquo;
	<?php echo ((is_array($_tmp='prdimport_step')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 2/2
	</h1>
	

	  
		<p><?php echo ((is_array($_tmp='prdimport_csv_desc2')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
		<p><?php echo ((is_array($_tmp='prdimport_csv_desc3')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
		<p><?php echo $this->_tpl_vars['source_column_count']; ?>
</p>
		<div "style="color:#555; padding: 15px; text-style: italic; background-color: #f3f3f3">
		<?php $_from = $this->_tpl_vars['source_columns']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['source_columns'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['source_columns']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['source_column_key'] => $this->_tpl_vars['source_column']):
        $this->_foreach['source_columns']['iteration']++;
?>
		<span style="font-style:italic;background-color:#eee;padding:0px;margin:10px 2px;"><?php echo $this->_tpl_vars['source_column']; ?>
</span><?php if (! ($this->_foreach['source_columns']['iteration'] == $this->_foreach['source_columns']['total'])): ?>, <?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
		</div>
		<br><br>


	  <form action="<?php echo ((is_array($_tmp='msg=')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">
	  	<input type="checkbox" name="use_structure" value="1" checked="checked" id="sc-csv-use-structure"><label for="sc-csv-use-structure"><?php echo ((is_array($_tmp='prdimport_csv_use_structure')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>
	  	<br><br><br>

		<?php echo $this->_tpl_vars['excel_import_configurator']; ?>


				<p><input type=submit name=do_excel_import value="<?php echo ((is_array($_tmp='btn_ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
		<input type=hidden name=dpt value=catalog>
		<input type=hidden name=sub value=excel_import>
		<input type=hidden name=number_of_titles_line value=$notl>
		<input type=hidden name=delimeter value="<?php echo $this->_tpl_vars['delimeter']; ?>
">
		<input type=hidden name=filename value="<?php echo $this->_tpl_vars['file_excel_name']; ?>
">

	  </form>



	<?php else: ?>

		<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>

		&raquo;
		<?php echo ((is_array($_tmp='prdimport_step')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 1/2
		</h1>
		<?php echo $this->_tpl_vars['MessageBlock']; ?>

		
		<p>
		<?php if ($this->_tpl_vars['excel_import_result'] == 'upload_file_error'):  echo ((is_array($_tmp='err_failed_to_upload_file')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?>
		<?php if ($this->_tpl_vars['excel_import_result'] == 'update_column_error'): ?><p><b><font color=red><?php echo ((is_array($_tmp='err_csvimport_update_column_is_not_set')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</font></b><?php endif; ?>
		</p>

		<form enctype="multipart/form-data" action="<?php echo ((is_array($_tmp='msg=')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="POST">
		<?php echo ((is_array($_tmp='prdimport_csv_desc1')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>


		<table cellpadding=3>
		<tr>
			<td><?php echo ((is_array($_tmp='prdimport_csv_browse_for_file')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td><input type="file" name="csv" /></td>
		</tr>
		<tr>
			<td>
			<?php echo ((is_array($_tmp='prdimport_csvl_delimeter')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			</td>
			<td>
				<select name="delimeter">
					<option value=";"><?php echo ((is_array($_tmp='prdimport_csv_delimeter_semicolon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (;)</option>
					<option value=","><?php echo ((is_array($_tmp='prdimport_csvl_delimeter_comma')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (,)</option>
					<option value="	"><?php echo ((is_array($_tmp='prdimport_csvl_delimeter_tab')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
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
		<input type=submit value="<?php echo ((is_array($_tmp='btn_ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
		<input type=hidden name=proceed value=1>
		<input type=hidden name=dpt value="catalog">
		<input type=hidden name=sub value="excel_import">

		</form>

	<?php endif;  endif; ?>