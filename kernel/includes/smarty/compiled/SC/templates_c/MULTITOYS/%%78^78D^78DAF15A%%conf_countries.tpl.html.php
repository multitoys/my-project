<?php /* Smarty version 2.6.9, created on 2015-10-02 13:55:06
         compiled from backend/conf_countries.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/conf_countries.tpl.html', 3, false),array('modifier', 'set_query_html', 'backend/conf_countries.tpl.html', 7, false),array('modifier', 'escape', 'backend/conf_countries.tpl.html', 25, false),array('function', 'cycle', 'backend/conf_countries.tpl.html', 20, false),array('function', 'html_text', 'backend/conf_countries.tpl.html', 22, false),)), $this); ?>

<h1><?php echo ((is_array($_tmp='pgn_countries')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post">

<table cellpadding="0" cellspacing="0" width="70%" class="grid">

	<tr class="gridsheader">
		<td><?php echo ((is_array($_tmp='usr_custinfo_country')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td width="1%" nowrap="nowrap"><?php echo ((is_array($_tmp='cntr_country_iso2')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td width="1%" nowrap="nowrap"><?php echo ((is_array($_tmp='cntr_country_iso3')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		<td width="1%">&nbsp;</td>
	</tr>

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

	<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
		<td>
			<?php echo smarty_function_html_text(array('style' => "width:100%;",'dbfield' => 'country_name','name' => "country_name_%lang%_".($this->_tpl_vars['countries'][$this->_sections['i']['index']]['countryID']),'values' => $this->_tpl_vars['countries'][$this->_sections['i']['index']]), $this);?>

		</td>
		<td align="center">
			<input type="text" name="country_iso2_<?php echo $this->_tpl_vars['countries'][$this->_sections['i']['index']]['countryID']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['i']['index']]['country_iso_2'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="3" />
		</td>
		<td align="center">
			<input type="text" name="country_iso3_<?php echo $this->_tpl_vars['countries'][$this->_sections['i']['index']]['countryID']; ?>
" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['countries'][$this->_sections['i']['index']]['country_iso_3'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="3" />
		</td>
		<td>
			<a href="javascript:confirmDelete(<?php echo $this->_tpl_vars['countries'][$this->_sections['i']['index']]['countryID']; ?>
,'<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
','<?php echo ((is_array($_tmp="delete=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
&amp;delete=');">
				<img src="images/remove.gif" border="0" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			</a>
		</td>
	</tr>

	<?php endfor; endif; ?>
	
	<?php if ($this->_tpl_vars['navigator']): ?>
	<tr class="gridsfooter">
		<td colspan="4"><?php echo $this->_tpl_vars['navigator']; ?>
</td>
	</tr>
	<?php endif; ?>

	<tr>
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr class="gridsheader">
		<td colspan="4" align="center"><?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	</tr>
	<tr class="gridline">
		<td><?php echo smarty_function_html_text(array('style' => "width:100%;",'name' => 'country_name'), $this);?>
</td>
		<td><input type="text" name="country_iso2" size="3" /></td>
		<td><input type="text" name="country_iso3" size="3" /></td>
		<td>&nbsp;</td>
	</tr>
	<tr class="gridsfooter">
		<td colspan="4">
		<br />
		<input type="hidden" name="dpt" value="conf" />
		<input type="hidden" name="sub" value="countries" />
		<input type="hidden" name="save_countries" value="yes" />
		<input type="submit" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
		</td>
	</tr>


</table>
</form>