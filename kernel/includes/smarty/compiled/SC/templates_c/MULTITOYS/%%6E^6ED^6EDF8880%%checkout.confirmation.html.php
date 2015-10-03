<?php /* Smarty version 2.6.9, created on 2015-09-22 08:31:12
         compiled from checkout.confirmation.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'checkout.confirmation.html', 4, false),array('modifier', 'escape', 'checkout.confirmation.html', 13, false),array('modifier', 'string_format', 'checkout.confirmation.html', 55, false),array('modifier', 'default', 'checkout.confirmation.html', 55, false),array('modifier', 'set_query_html', 'checkout.confirmation.html', 77, false),array('modifier', 'replace', 'checkout.confirmation.html', 90, false),array('function', 'cycle', 'checkout.confirmation.html', 41, false),)), $this); ?>
	<?php echo $this->_tpl_vars['MessageBlock']; ?>

	<?php if ($this->_tpl_vars['payment_error']): ?>
	<p>
		<font color="red"><b><?php echo ((is_array($_tmp='err_payment_processing')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  if ($this->_tpl_vars['payment_error'] != 1): ?>: <?php echo $this->_tpl_vars['payment_error'];  endif; ?></b></font>
	</p>
	<?php endif; ?>

	<table cellspacing="0" cellpadding="15">
	<?php if (( $this->_tpl_vars['orderSum']['shipping_name'] && $this->_tpl_vars['orderSum']['shipping_name'] != "-" ) || ( $this->_tpl_vars['orderSum']['payment_name'] && $this->_tpl_vars['orderSum']['payment_name'] != "-" )): ?>
	<tr>
	<?php if ($this->_tpl_vars['orderSum']['shipping_name'] && $this->_tpl_vars['orderSum']['shipping_name'] != "-"): ?>
		<td>
			<?php echo ((is_array($_tmp='shipping')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<strong><?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['shipping_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  if ($this->_tpl_vars['orderSum']['shippingServiceInfo']): ?> - <?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['shippingServiceInfo'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?></strong>):
			<p><strong><?php echo $this->_tpl_vars['orderSum']['shippingAddress']->getHTMLString(); ?>
</strong></p>
		</td>
	<?php endif; ?>
	<?php if ($this->_tpl_vars['orderSum']['payment_name'] && $this->_tpl_vars['orderSum']['payment_name'] != "-"): ?>
		<td>
			<?php echo ((is_array($_tmp='payment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<strong><?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['payment_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</strong>):
			<p><strong><?php echo $this->_tpl_vars['orderSum']['billingAddress']->getHTMLString(); ?>
</strong></p>
		</td>
	<?php endif; ?>
	</tr>
	<?php endif; ?>

	<tr>
	<td colspan="2">

		<table id="cart_content_tbl" cellspacing="0" width="100%">
		<tr id="cart_content_header">
			<td>&nbsp;</td>
			<?php if (@CONF_ENABLE_PRODUCT_SKU): ?>
			<td style='text-align:center'>Артикул</td>
			<?php endif; ?>
			<td style='text-align:center'>Наименование</td>
			<td style='text-align:center'><?php echo ((is_array($_tmp='cart_product_quantity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td style='text-align:center'><?php echo ((is_array($_tmp='ordr_itemprice_excluding_tax')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
		</tr>

		<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['orderSum']['sumOrderContent']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
		<tr class='row_<?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?>
'>
			<td align="center" valign="top" width="1%"><?php if ($this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['thumbnail_url']): ?><img src="<?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['thumbnail_url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" width="<?php echo $this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['thumbnail_width']; ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" /><?php else: ?>&nbsp;<?php endif; ?></td>
			<?php if (@CONF_ENABLE_PRODUCT_SKU): ?>
			<td><?php if ($this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['product_code']):  echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  endif; ?></td>
			<?php endif; ?>
			<td><?php echo ((is_array($_tmp=$this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
			<td style='text-align:center'><?php echo $this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['quantity']; ?>
</td>
			<td style='text-align:center' nowrap="nowrap"><?php echo $this->_tpl_vars['orderSum']['sumOrderContent'][$this->_sections['i']['index']]['cost']; ?>
</td>
		</tr>
		<?php endfor; endif; ?>
		<tr><td colspan="5">&nbsp;</td></tr>

		<?php if ($this->_tpl_vars['orderSum']['discount'] > 0): ?>
		<tr>
			<td colspan="4"><?php echo ((is_array($_tmp='usr_custinfo_group_discount')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['orderSum']['discount_percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, ', %0.1f%%') : smarty_modifier_string_format($_tmp, ', %0.1f%%')))) ? $this->_run_mod_handler('default', true, $_tmp, 'nbsp;') : smarty_modifier_default($_tmp, 'nbsp;')); ?>
</td>
			<td align="center">-<?php echo $this->_tpl_vars['orderSum']['discount_str']; ?>
</td>
		</tr>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['orderSum']['shipping_costUC']): ?>
		<tr>
			<td colspan="4"><?php echo ((is_array($_tmp='shipping')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<?php echo $this->_tpl_vars['orderSum']['shipping_name']; ?>
)</td>
			<td align="center"><?php echo $this->_tpl_vars['orderSum']['shipping_cost']; ?>
</td>
		</tr>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['orderSum']['totalTaxUC']): ?>
		<tr>
			<td colspan="4"><?php echo ((is_array($_tmp='ordr_tax')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td align="center"><?php echo $this->_tpl_vars['orderSum']['totalTax']; ?>
</td>
		</tr>
		<?php endif; ?>
		<tr>
			<td id="cart_total_label" colspan="4"><?php echo ((is_array($_tmp='str_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td id="cart_total"  style='text-align:center'><?php echo $this->_tpl_vars['orderSum']['total']; ?>
</td>
		</tr>
		</table>

		<form id=order_submit action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" name='MainForm' method="POST">
		<input type="hidden" name='totalUC' value='<?php echo $this->_tpl_vars['totalUC']; ?>
' />
		<input name="action" value="process_order" type="hidden" />
<?php if ($this->_tpl_vars['orderSum']['payment_form_html']): ?>
			<div style="padding: 20px;" class="background1"><?php echo $this->_tpl_vars['orderSum']['payment_form_html']; ?>
</div>
<?php endif; ?>
			<p>
				<?php echo ((is_array($_tmp='checkout_comment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br />
				<textarea name="order_comment" cols="70" rows="3"></textarea>
			</p>

		<?php if ($this->_tpl_vars['OrdersNumOverflow']): ?>
			<p><input type="submit" name="submit" value="<?php echo ((is_array($_tmp='checkout_place_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" disabled="disabled"/></p>
			<div class="error_msg_f"><?php echo ((is_array($_tmp=@TRIAL_STRING_ORDERS_OVERFLOW)) ? $this->_run_mod_handler('replace', true, $_tmp, '[NUM]', @TRIAL_MAX_ORDERS_NUM) : smarty_modifier_replace($_tmp, '[NUM]', @TRIAL_MAX_ORDERS_NUM)); ?>
</div>
			<p><?php echo @TRIAL_STRING_LIMITATIONS; ?>

		<?php else: ?>
			<p><input type="submit" class="checkout_buttons blue-button check" id="checkout_button" name="submit" value="<?php echo ((is_array($_tmp='checkout_place_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"
			onclick="disable_button();"/></p>
		<?php endif; ?>

		</form>
	</td>

</tr></table>