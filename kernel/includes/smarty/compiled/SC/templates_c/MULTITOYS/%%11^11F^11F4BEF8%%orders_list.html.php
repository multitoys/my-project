<?php /* Smarty version 2.6.9, created on 2015-09-22 08:33:05
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'transcape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 4, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 5, false),array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 69, false),array('modifier', 'default', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 76, false),array('modifier', 'escape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 108, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/orders_list.html', 72, false),)), $this); ?>
<table border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top"><h1><?php echo ((is_array($_tmp='ordr_order_list')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</h1></td>
    <td valign="top" style="padding-left: 25px;"><form><button type="button" onClick="window.location='<?php echo $this->_tpl_vars['ocrt_url']; ?>
';"><?php echo ((is_array($_tmp='pgn_order_creater')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</button></form></td>
</tr>
</table>
<?php echo $this->_tpl_vars['MessageBlock']; ?>

<?php if ($this->_tpl_vars['page_enabled']): ?>
	
		<form method="get" action="" name="MainForm">
			<input type="hidden" name="did" value="<?php echo $this->_tpl_vars['CurrentDivision']['id']; ?>
" />
			<table>
				<tr>
					<td valign="top">
						<?php if ($this->_tpl_vars['order_statuses']): ?>
							<input class="inlradio" type="radio" name="order_search_type" value="SearchByStatusID" <?php if ($this->_tpl_vars['order_search_type'] == 'SearchByStatusID'): ?>checked="checked"<?php endif; ?> onclick="order_search_typeClickHandler()" id="id_order_search_type2" /><label for="id_order_search_type2"> <?php echo ((is_array($_tmp='ordr_filter_by_status')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>:
							<?php $_from = $this->_tpl_vars['order_statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_status']):
?>
							<div style="margin-left:20px;margin-top:3px;margin-bottom:3px;">
								<input class="checknomarging" type="checkbox" name="checkbox_order_status_<?php echo $this->_tpl_vars['_status']['statusID']; ?>
" id="id_checkbox_order_status_<?php echo $this->_tpl_vars['_status']['statusID']; ?>
"<?php if ($this->_tpl_vars['_status']['selected'] == 1): ?> checked="checked"<?php endif; ?> value="1" /><label for="id_checkbox_order_status_<?php echo $this->_tpl_vars['_status']['statusID']; ?>
" style="<?php echo $this->_tpl_vars['_status']['_style']; ?>
"> <?php echo $this->_tpl_vars['_status']['status_name']; ?>
</label>
        						<span style="color: #AAAAAA;">(<?php echo $this->_tpl_vars['_status']['orders_count']; ?>
)</span>
							</div>
							<?php endforeach; endif; unset($_from); ?>
						<?php endif; ?>
					</td>
					<td valign="top">
						<input class="inlradio" type="radio" name="order_search_type" value="SearchByOrderID" <?php if ($this->_tpl_vars['order_search_type'] == 'SearchByOrderID' || $this->_tpl_vars['order_search_type'] == null): ?> checked="checked" <?php endif; ?>	onclick="order_search_typeClickHandler()" id="id_order_search_type1" /><label for="id_order_search_type1"> <?php echo ((is_array($_tmp='ordr_search_by_id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</label>:
						<div style="margin-left:20px;margin-top:3px;margin-bottom:3px;">
							<input type="text" name="orderID_textbox" value="<?php echo $this->_tpl_vars['orderID']; ?>
" />
						</div>
					</td>
				</tr>
				<tr>
					<td valign="top" colspan="2">
						<input type="submit" value="<?php echo ((is_array($_tmp='btn_show')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" name="search" />
					</td>
				</tr>
			</table>

			<script language="JavaScript" type="text/javascript">
				function order_search_typeClickHandler()
				<?php echo '{'; ?>

			
					if ( document.MainForm.order_search_type[1].checked )
					<?php echo '{'; ?>

						<?php $_from = $this->_tpl_vars['order_statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_status']):
?>
							document.MainForm.checkbox_order_status_<?php echo $this->_tpl_vars['_status']['statusID']; ?>
.disabled = true;
						<?php endforeach; endif; unset($_from); ?>
						document.MainForm.orderID_textbox.disabled = false;
					<?php echo '}'; ?>

					else if ( document.MainForm.order_search_type[0].checked )
					<?php echo '{'; ?>

						<?php $_from = $this->_tpl_vars['order_statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_status']):
?>
							document.MainForm.checkbox_order_status_<?php echo $this->_tpl_vars['_status']['statusID']; ?>
.disabled = false;
						<?php endforeach; endif; unset($_from); ?>
						document.MainForm.orderID_textbox.disabled = true;
					<?php echo '}'; ?>

				<?php echo '}'; ?>

			
				order_search_typeClickHandler();
			</script>

	<p>
	<?php if ($this->_tpl_vars['orders']): ?>
		<div>
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="grid">
			<tr class="gridsheader"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/gridheader.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></tr>
			<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['orders']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
				<?php $this->assign('order_url', ((is_array($_tmp="ukey=admin_order_detailed&orderID=".($this->_tpl_vars['orders'][$this->_sections['i']['index']]['orderID'])."&rdid=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
				<?php $this->assign('order_statusID', $this->_tpl_vars['orders'][$this->_sections['i']['index']]['statusID']); ?>

				<tr class="<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
">
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['orderID_view']; ?>
</a></td>
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['order_time']; ?>
</a></td>
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['customer_firstname']; ?>
 <?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['customer_lastname']; ?>
</a></td>
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['orders'][$this->_sections['i']['index']]['payment_type'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</a></td>
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['orders'][$this->_sections['i']['index']]['shipping_type'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;'));  if ($this->_tpl_vars['orders'][$this->_sections['i']['index']]['shippingServiceInfo']): ?> (<?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['shippingServiceInfo']; ?>
)<?php endif; ?></a></td>
					<td><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo $this->_tpl_vars['orders'][$this->_sections['i']['index']]['order_amount']; ?>
</a></td>
					<td class="endgrid"><a href='<?php echo $this->_tpl_vars['order_url']; ?>
' style="<?php echo $this->_tpl_vars['order_statuses'][$this->_tpl_vars['order_statusID']]['_style']; ?>
"><?php echo ((is_array($_tmp=@$this->_tpl_vars['orders'][$this->_sections['i']['index']]['OrderStatus'])) ? $this->_run_mod_handler('default', true, $_tmp, '&nbsp;') : smarty_modifier_default($_tmp, '&nbsp;')); ?>
</a></td>
				</tr>
			<?php endfor; endif; ?>
			<tr class="gridsfooter">
				<td colspan="7"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/lister.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
			</tr>
			<tr class="gridsfooter">
				<td colspan="5">
				<?php echo $this->_tpl_vars['TotalFound']; ?>
<br>
				<?php if ($this->_tpl_vars['order_statuses']): ?>
					<?php $_from = $this->_tpl_vars['order_statuses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_status']):
?>
					<?php if ($this->_tpl_vars['_status']['selected'] == 1): ?> 
						<span style="<?php echo $this->_tpl_vars['_status']['_style']; ?>
"> <?php echo $this->_tpl_vars['_status']['status_name']; ?>
:<?php echo $this->_tpl_vars['_status']['orders_count']; ?>
 (<?php echo $this->_tpl_vars['_status']['orders_amount_display']; ?>
)</span>
						<br/>
					<?php endif; ?>
					<?php endforeach; endif; unset($_from); ?>
				<?php endif; ?>
					<span style="font-weight: bold;"><?php echo ((is_array($_tmp='str_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['TotalCount']; ?>
 (<?php echo $this->_tpl_vars['total_statuses_amount']; ?>
)</span>
				</td>
				<td colspan="2">&nbsp;</td>
			</tr>
		</table>
		<p>* <small><?php echo ((is_array($_tmp='ordr_msg_about_prices')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</small></p>			
<!--		<?php if ($this->_tpl_vars['orders_has_been_exported_succefully'] != 1): ?>
		<br>
		<p><?php echo ((is_array($_tmp='export_orderlist_to_csv')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
		<p><?php echo ((is_array($_tmp='prdimport_file_charset')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			<select name="charset">
				<?php $_from = $this->_tpl_vars['charsets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_charset']):
?>
					<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['default_charset'] == $this->_tpl_vars['_charset']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			<input type=submit name='export_to_excel' value='<?php echo ((is_array($_tmp='btn_export')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
			</p>
-->			
		<?php endif; ?>
		</div>
	<?php else: ?>
		<div><p><?php echo ((is_array($_tmp='srch_no_matches_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p></div>
	<?php endif; ?>
	
</form>


<?php endif; ?>