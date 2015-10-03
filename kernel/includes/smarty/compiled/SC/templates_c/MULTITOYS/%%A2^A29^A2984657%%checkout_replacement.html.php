<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:58
         compiled from backend/checkout_replacement.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/checkout_replacement.html', 1, false),array('modifier', 'transcape', 'backend/checkout_replacement.html', 12, false),array('modifier', 'set_query_html', 'backend/checkout_replacement.html', 17, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<p><?php echo ((is_array($_tmp='chckrpl_page_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<?php $_from = $this->_tpl_vars['modules']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_module']):
?>
<div class="chckrpl_module_block">
	<table>
	<tr>
		<td><h2><?php echo $this->_tpl_vars['_module']['moduleEntry']->title; ?>
</h2></td>
		<td align="right"><input class="chckrpl_module_switch" rel="<?php echo $this->_tpl_vars['_module']['module_id']; ?>
" type="button" value="<?php if (! $this->_tpl_vars['_module']['enabled']):  echo ((is_array($_tmp='btn_enable')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp));  else:  echo ((is_array($_tmp='btn_disable')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp));  endif; ?>" /></td>
	</tr>
	<tr class="chckrpl_module_settings" <?php if (! $this->_tpl_vars['_module']['enabled']): ?>style="display: none;"<?php endif; ?> id="chckrpl-settings-<?php echo $this->_tpl_vars['_module']['module_id']; ?>
">
		<td colspan="2">
		<?php if ($this->_tpl_vars['_module']['description']): ?><p><?php echo $this->_tpl_vars['_module']['description']; ?>
</p><?php endif; ?>
		<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post" enctype="multipart/form-data">
			<input name="action" value="save_module_settings" type="hidden" />
			<input name="module_id" value="<?php echo $this->_tpl_vars['_module']['module_id']; ?>
" type="hidden" />
		
			<table cellpadding="5" cellspacing="0">
			<tbody class="chckrpl_module_settings">
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['_module']['settings']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<td width="250"><?php echo $this->_tpl_vars['_module']['settings'][$this->_sections['i']['index']]['settings_title']; ?>
:
					<div class="notice"><?php echo $this->_tpl_vars['_module']['settings'][$this->_sections['i']['index']]['settings_description']; ?>
</div>
				</td>
				<td>
					<?php echo $this->_tpl_vars['_module']['controls'][$this->_sections['i']['index']]; ?>

				</td>
			</tr>
			<?php endfor; endif; ?>
			</tbody>
			</table>
			<input value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" type="submit" />
		</form>
		</td>
	</tr>
	</table>
</div>
<?php endforeach; endif; unset($_from); ?>

<script type="text/javascript" src="<?php echo @URL_JS; ?>
/niftycube.js"></script>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/JsHttpRequest.js"></script>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/checkout_replacement.js"></script>
<script type="text/javascript">
translate.btn_enable = "<?php echo ((is_array($_tmp='btn_enable')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
";
translate.btn_disable = "<?php echo ((is_array($_tmp='btn_disable')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
";
</script>