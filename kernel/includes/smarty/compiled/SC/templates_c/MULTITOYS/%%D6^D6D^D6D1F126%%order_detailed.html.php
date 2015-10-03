<?php /* Smarty version 2.6.9, created on 2015-09-22 08:06:58
         compiled from order_detailed.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'order_detailed.html', 3, false),array('modifier', 'escape', 'order_detailed.html', 4, false),array('modifier', 'translate', 'order_detailed.html', 7, false),array('modifier', 'set_query', 'order_detailed.html', 142, false),array('modifier', 'transcape', 'order_detailed.html', 218, false),array('modifier', 'string_format', 'order_detailed.html', 218, false),array('modifier', 'replace', 'order_detailed.html', 282, false),array('function', 'cycle', 'order_detailed.html', 180, false),)), $this); ?>
<?php if ($this->_tpl_vars['order']):  if (! $this->_tpl_vars['printable_version'] && $this->_tpl_vars['PAGE_VIEW'] != 'mobile'): ?>
<form action="<?php echo ((is_array($_tmp="view=printable")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post"  target="_blank">
<input type="hidden" name="customer_last_name" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
<input type="hidden" name="customer_email" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
<input type="hidden" name="orderID" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['orderID'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
<input type='submit' value='<?php echo ((is_array($_tmp='btn_open_invoice')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' onclick="open_window('<?php echo ((is_array($_tmp="view=printable&orderID=".($this->_tpl_vars['order']['orderID'])."&customer_last_name=".($this->_tpl_vars['order']['customer_lastname'])."&customer_email=".($this->_tpl_vars['order']['customer_email']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
',700,500); return false"/>
</form>
<?php endif; ?>
		<table border="0" cellspacing="1" cellpadding="10" width="98%">
		<tr>
			<td>
				<strong><font size="+1"><u><?php echo ((is_array($_tmp='ordr_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></font></strong>
			</td>
			<td>
				<strong><font size="+1"><u><?php echo ((is_array($_tmp='payment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></font></strong>
			</td>
			<td>
				<strong><font size="+1"><u><?php echo ((is_array($_tmp='shipping')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></font></strong>
			</td>
			<td class="noprint"><?php if (! $this->_tpl_vars['printable_version'] && $this->_tpl_vars['PAGE_VIEW'] != 'mobile'): ?>
				<strong><font size="+1"><u><?php echo ((is_array($_tmp='print_forms')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></font></strong>
				<?php else: ?>&nbsp;
				<?php endif; ?>
			</td>
		</tr>

		<tr valign="top">
			<td>
				<table>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['orderID_view'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_order_time')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['order_time'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_customer')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>
					<tr>
						<td><?php echo ((is_array($_tmp='usr_custinfo_email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customer_email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_customer_ip')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo $this->_tpl_vars['order']['customer_ip']; ?>
</b></td>
					</tr>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_status')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['status_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>

					<?php if ($this->_tpl_vars['order']['customers_comment'] != ""): ?>
					<tr>
						<td><?php echo ((is_array($_tmp='ordr_comment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['customers_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
					</tr>
					<?php endif; ?>

				</table>
			</td>
			<td>
				<table>
				<tr>
					<td><?php echo ((is_array($_tmp='payment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['payment_type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
				</tr>
				<tr>
					<td><?php echo ((is_array($_tmp='ordr_payee')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
				</tr>
				<tr>
					<td><?php echo ((is_array($_tmp='ordr_billing_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: 
						<b>
						<?php if (@CONF_ADDRESSFORM_STATE != 2): ?>
						<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_address'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 
						<?php endif; ?>
						<?php if (@CONF_ADDRESSFORM_CITY != 2 || @CONF_ADDRESSFORM_ADDRESS != 2 || @CONF_ADDRESSFORM_ZIP != 2): ?><br /><?php endif; ?>
						<?php if (@CONF_ADDRESSFORM_CITY != 2): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_city'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php endif; ?>
						<?php if (@CONF_ADDRESSFORM_ADDRESS != 2): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_state'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php endif; ?>
						<?php if (@CONF_ADDRESSFORM_ZIP != 2): ?>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_zip'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php endif; ?>
						<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['billing_country'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						</b>
					</td>
				</tr>
				<?php if (! $this->_tpl_vars['printable_version'] && $this->_tpl_vars['payment_html']): ?>
				<tr>
				<td class="background1"><?php echo $this->_tpl_vars['payment_html']; ?>
</td>
				</tr><?php endif; ?>
				</table>
			</td>
		<td>
			<table>
			<tr>
				<td><?php echo ((is_array($_tmp='shipping')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_type'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b> <?php if ($this->_tpl_vars['order']['shippingServiceInfo']): ?>(<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shippingServiceInfo'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
)<?php endif; ?></td>
			</tr>
			<tr>
				<td><?php echo ((is_array($_tmp='ordr_recipient')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <b><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_firstname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_lastname'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</b></td>
			</tr>
			<tr>
				<td><?php echo ((is_array($_tmp='ordr_shipping_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: 
					<b>
					<?php if (@CONF_ADDRESSFORM_STATE != 2): ?>
					<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_address'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					<?php endif; ?>
					<?php if (@CONF_ADDRESSFORM_CITY != 2 || @CONF_ADDRESSFORM_ADDRESS != 2 || @CONF_ADDRESSFORM_ZIP != 2): ?><br /><?php endif; ?>
					<?php if (@CONF_ADDRESSFORM_CITY != 2): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_city'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					<?php endif; ?>
					<?php if (@CONF_ADDRESSFORM_ADDRESS != 2): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_state'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					<?php endif; ?>
					<?php if (@CONF_ADDRESSFORM_ZIP != 2): ?>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_zip'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 
					<?php endif; ?>
					<br /><?php echo ((is_array($_tmp=$this->_tpl_vars['order']['shipping_country'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					</b>
				</td>
			</tr>
			</table>
		</td>
	
	<td class="noprint" style="padding-right: 15px;" valign="top">
	<?php if (! $this->_tpl_vars['printable_version'] && $this->_tpl_vars['PAGE_VIEW'] != 'mobile'): ?>
	<?php if ($this->_tpl_vars['print_forms']): ?>
	<script type="text/javascript">
<?php echo '
function show_printforms()
{
	var boxes = getElementsByClass(\'printforms\', document, \'input\');
	var win = "menubar=no,location=no,resizable=yes,scrollbars=yes";
	for(var i_max = boxes.length-1; i_max>=0; i_max--){
		if(boxes[i_max].checked){
			window.open(boxes[i_max].value, \'printableWin\'+i_max, win);
		}
	}
	return false;
}
'; ?>

</script>
		<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
">
		<table>
			<?php $_from = $this->_tpl_vars['print_forms']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['print_form_class'] => $this->_tpl_vars['print_form']):
?>
			<tr>
				<td><input type="checkbox" checked="checked" class="printforms" id="print_<?php echo $this->_tpl_vars['print_form_class']; ?>
"
				 name="printforms[<?php echo $this->_tpl_vars['print_form_class']; ?>
]" 
				 value="<?php echo ((is_array($_tmp="?ukey=print_form&orderID=".($this->_tpl_vars['order']['orderID'])."&form_class=".($this->_tpl_vars['print_form_class'])."&order_time=".($this->_tpl_vars['order']['order_time_encoded'])."&customer_email=".($this->_tpl_vars['order']['customer_email_encoded']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
">
				</td>
				<td>
					<label for="print_<?php echo $this->_tpl_vars['print_form_class']; ?>
"><?php echo $this->_tpl_vars['print_form']['name']; ?>
</label>
				</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
			<tr class="gridsfooter"><td colspan="2"><input id="printforms" class="my-button" onclick="show_printforms();" type="button" value="<?php echo ((is_array($_tmp='btn_print')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"></td></tr>
		</table>
		</form>
		<?php else: ?>
		&lt;<?php echo ((is_array($_tmp='str_empty_list')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
&gt;
		<?php endif; ?>
	<?php else: ?>&nbsp;
	<?php endif; ?>
	</td>	
	</tr>
	<tr valign="top"> 
       	<td colspan="4">&nbsp;</td>
    </tr>
	<tr valign="top"> 
       	<td colspan="4"><strong><font size="+1"><u><?php echo ((is_array($_tmp='ordr_ordered_products')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</u></font></strong></td>
    </tr>
	<tr>
		<td colspan="4">
			<table width="100%" cellpadding="6" cellspacing="0">
			<tr class="background1"> 
         		<td><strong><?php echo ((is_array($_tmp='prdset_product_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
         		<td align="center"><strong><?php echo ((is_array($_tmp='cart_product_quantity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
         		<td align="right"><strong><?php echo ((is_array($_tmp='ordr_itemprice_excluding_tax')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
       		</tr>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['orderContent']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
			<tr class="<?php echo smarty_function_cycle(array('values' => 'row_odd,row_even'), $this);?>
"> 
             	<td>
					<?php echo ((is_array($_tmp=$this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					<?php if ($this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['eproduct_filename']): ?>
						<?php if (@CONF_ORDSTATUS_DELIVERED == $this->_tpl_vars['order']['statusID']): ?>

								<a href='<?php echo @URL_ROOT; ?>
/get_file.php?getFileParam=<?php echo $this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['getFileParam']; ?>
'>
									<?php echo ((is_array($_tmp='btn_download')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo $this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['file_size_str']; ?>

								</a>

								<?php if ($this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['day_count_remainder'] > 0): ?>
									- <?php echo ((is_array($_tmp='prdset_download_is_available_for')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
										<?php echo ((is_array($_tmp=$this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['day_count_remainder'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

									<?php echo ((is_array($_tmp='msg_days')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

									<?php if ($this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['load_counter_remainder'] != 0): ?>
										,
										<?php echo ((is_array($_tmp='prd_download_downloads_left')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 
											<?php echo ((is_array($_tmp=$this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['load_counter_remainder'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

										<?php echo ((is_array($_tmp='prd_download_str_downloads')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

									<?php endif; ?>
								<?php endif; ?>
						<?php endif; ?>
					<?php endif; ?>
				</td>
             	<td align="center"><?php echo ((is_array($_tmp=$this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['Quantity'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
             	<td align="right"><?php echo ((is_array($_tmp=$this->_tpl_vars['orderContent'][$this->_sections['i']['index']]['PriceToShow'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
           	</tr>
			<?php endfor; endif; ?>
			<tr><td colspan="3">&nbsp;</td>
			
			<?php if ($this->_tpl_vars['order']['order_discount']): ?>
			<tr>
				<td colspan="2"><?php echo ((is_array($_tmp='usr_custinfo_group_discount')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp));  echo ((is_array($_tmp=$this->_tpl_vars['order']['order_discount_percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, ', %0.1f%%') : smarty_modifier_string_format($_tmp, ', %0.1f%%')); ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['order']['order_discount_valueToShow']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['order']['shipping_cost'] || $this->_tpl_vars['order']['tax']): ?>
			<tr>
				<td colspan="2"><?php echo ((is_array($_tmp='ordr_subtotal')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['order']['clear_total_priceToShow']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['order']['shipping_cost']): ?>
			<tr>
				<td colspan="2"><?php echo ((is_array($_tmp='ordr_shipping_handling_cost')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['order']['shipping_costToShow']; ?>
</td>
			</tr>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['order']['tax']): ?>
			<tr>
				<td colspan="2"><?php echo ((is_array($_tmp='ordr_tax')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['order']['tax_toShow']; ?>
</td>
			</tr>
			<?php endif; ?>
			<tr id="ord_total_row">
				<td colspan="2"><?php echo ((is_array($_tmp='str_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
				<td align="right"><?php echo $this->_tpl_vars['order']['order_amountToShow']; ?>
</td>
			</tr>
			</table>

		</td>
	</tr>

	<?php if ($this->_tpl_vars['order_status_report']): ?>
	<tr>
		<td colspan="4">
			&nbsp;
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<h3><?php echo ((is_array($_tmp='ordr_order_processing_history')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h3>
		</td>
	</tr>
	<tr>
		<td colspan="4">
			<table width="100%" border="0" cellpadding="5" cellspacing="1">
				<tr>
					<td>
						<b><?php echo ((is_array($_tmp='str_time')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
					</td>
					<td>
						<b><?php echo ((is_array($_tmp='ordr_comment')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
					</td>
					<td>
						<b><?php echo ((is_array($_tmp='str_status')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
					</td>
				</tr>

				<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['order_status_report']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<tr class="<?php echo smarty_function_cycle(array('values' => 'row_odd,row_even'), $this);?>
">
					<td>
						<?php echo ((is_array($_tmp=$this->_tpl_vars['order_status_report'][$this->_sections['i']['index']]['status_change_time'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

					</td>
					<td>
						<?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['order_status_report'][$this->_sections['i']['index']]['status_comment'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('replace', true, $_tmp, "\n", '<br>') : smarty_modifier_replace($_tmp, "\n", '<br>')); ?>

					</td>
					<td>
						<?php if ($this->_tpl_vars['order_status_report'][$this->_sections['i']['index']]['status_name'] != ((is_array($_tmp='ordr_status_cancelled')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp))): ?>
							<?php echo ((is_array($_tmp=$this->_tpl_vars['order_status_report'][$this->_sections['i']['index']]['status_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

						<?php else: ?>
							<?php echo ((is_array($_tmp='ordr_status_cancelled')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

						<?php endif; ?>
					</td>
				</tr>
				<?php endfor; endif; ?>

			</table>

		</td>
	</tr>
	<?php endif; ?>

	<tr>
		<td colspan="4">
			

			<input type=hidden name='orders_detailed' value='yes'>
			
		</td>
	</tr>
		</table>
<?php else:  if (! $this->_tpl_vars['silent']): ?><p><?php echo ((is_array($_tmp='lbl_not_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p><?php endif;  endif; ?>