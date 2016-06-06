<?php /* Smarty version 2.6.9, created on 2016-06-06 14:24:27
         compiled from currency_selection.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'currency_selection.html', 3, false),)), $this); ?>
<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post" name="ChangeCurrencyForm">
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
		<input type="radio" name='current_currency' value='<?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID']; ?>
' id='currency-<?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID']; ?>
' <?php if ($this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID'] == $this->_tpl_vars['current_currency']): ?> checked<?php endif; ?> onchange='_changeCurrency()'><label for='currency-<?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['CID']; ?>
'><?php echo $this->_tpl_vars['currencies'][$this->_sections['i']['index']]['Name']; ?>
</label>
		<?php endfor; endif; ?>
</form>