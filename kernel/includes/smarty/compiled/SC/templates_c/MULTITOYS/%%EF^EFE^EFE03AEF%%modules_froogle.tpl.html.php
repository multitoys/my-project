<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:31
         compiled from backend/modules_froogle.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/modules_froogle.tpl.html', 5, false),)), $this); ?>

<?php if ($this->_tpl_vars['froogle_export_successful'] == 1): ?>
<p>
<font color=blue class=cat><b><?php echo ((is_array($_tmp='gglbase_msg_exported_products')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b></font><br>

<p>
<a href="get_file.php?getFileParam=<?php echo $this->_tpl_vars['getFileParam']; ?>
"><?php echo ((is_array($_tmp='btn_download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a> (<?php echo $this->_tpl_vars['froogle_filesize']; ?>
 Kb)

<?php else: ?>

<p>
<?php if ($this->_tpl_vars['froogle_errormsg'] != NULL): ?>
<font color=red><b><?php echo $this->_tpl_vars['froogle_errormsg']; ?>
</b></font>
<?php endif; ?>

<h1><?php echo ((is_array($_tmp='pgn_export2googlebase')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<form action="" method=post name="form_export">

	<ol type="1">
		<li>
			<b class="header2"><?php echo ((is_array($_tmp='str_choose_products')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/product_tree.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</li>
		<li>
			<b class="header2"><?php echo ((is_array($_tmp='prdcat_advsearch_customoption_availablevalues')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
			<p><?php echo ((is_array($_tmp='gglbase_pricing_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			<br>
			<?php echo ((is_array($_tmp='gglbase_usd_currency_type')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
			<select name='froogle_currency' onchange='JavaScript:_changeCurrency()'>
				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['currencies']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
					<option value='<?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID']; ?>
'
						<?php if ($this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID'] == @CONF_DEFAULT_CURRENCY): ?>
							selected
						<?php endif; ?>
					>
						<?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['Name']; ?>

					</option>
				<?php endfor; endif; ?>
			</select>
		
			<p>
			<?php echo ((is_array($_tmp='gglbase_description_hint1')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		
			<select name=froogle_export_description>
				<option value=2 selected><?php echo ((is_array($_tmp='prdset_description_brief')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				<option value=0><?php echo ((is_array($_tmp='prdset_meta_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				<option value=1><?php echo ((is_array($_tmp='str_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
			</select>
			<br>
			<B><?php echo ((is_array($_tmp='gglbase_description_hint2')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</B>
		</li>
	</ol>

	<p>
	<input type="button" onclick="document.form_export.elements['froogle_export'].value='froogle_export'; document.form_export.submit(); return false;" value="<?php echo ((is_array($_tmp='gglbase_btn_create_feed')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
	<input type=hidden name=dpt value=modules>
	<input type=hidden name=sub value=froogle>
	<input type=hidden name=froogle_export value=''>

</form>

<?php endif; ?>