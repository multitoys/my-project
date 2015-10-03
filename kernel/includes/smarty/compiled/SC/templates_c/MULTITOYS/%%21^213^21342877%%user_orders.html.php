<?php /* Smarty version 2.6.9, created on 2015-09-23 10:06:14
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/user_orders.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/user_orders.html', 8, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/user_orders.html', 24, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/user_orders.html', 9, false),)), $this); ?>
<?php if ($this->_tpl_vars['GridRows']): ?>
<table class="grid" width="100%">
<tr class="gridsheader">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/gridheader.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</tr>
	
<?php $_from = $this->_tpl_vars['GridRows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
 $this->assign('_order_url', ((is_array($_tmp="did=&ukey=admin_order_detailed&orderID=".($this->_tpl_vars['_row']['orderID'])."&rdid=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
<tr class="<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
">
	<td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['orderID_view']; ?>
</a></td>
	<td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['order_time']; ?>
</a></td>
    <td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['customer_ip']; ?>
</a></td>
    	<td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['order_amount']; ?>
</a></td>
    <td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['shipping_type'];  if ($this->_tpl_vars['_row']['shippingServiceInfo']): ?> (<?php echo $this->_tpl_vars['_row']['shippingServiceInfo']; ?>
)<?php endif; ?></a></td>
    <td><a href='<?php echo $this->_tpl_vars['_order_url']; ?>
' style="<?php echo $this->_tpl_vars['_row']['_style']; ?>
"><?php echo $this->_tpl_vars['_row']['status_name']; ?>
</a></td>
</tr>	
<?php endforeach; endif; unset($_from); ?>
<tr style="height: 1px; background-color: #CCCCCC;"><td colspan="6" style="padding: 0px;"></td></tr>
<?php if ($this->_tpl_vars['orders_totals']): ?>
<tr>
    <td colspan="4"></td>
    <td colspan="2">
        <span style="font-size: 120%"><?php echo ((is_array($_tmp='str_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</span>
        <?php $_from = $this->_tpl_vars['orders_totals']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['ot']):
?>
        <br />
        <span style="color: <?php echo $this->_tpl_vars['ot']['color']; ?>
;<?php if ($this->_tpl_vars['ot']['bold']): ?> font-weight: bold;<?php endif;  if ($this->_tpl_vars['ot']['italic']): ?> font-style: italic;<?php endif; ?>"><?php echo $this->_tpl_vars['ot']['status_name']; ?>
:</span>
        <span style="color: <?php echo $this->_tpl_vars['ot']['color']; ?>
; font-weight: bold;"><?php echo $this->_tpl_vars['default_currency_code']; ?>
 <?php echo $this->_tpl_vars['ot']['total']; ?>
</span>
        <?php endforeach; endif; unset($_from); ?>
    </td>
</tr>
<?php endif; ?>

<tr class="gridsfooter"> 
	<td colspan="<?php echo $this->_tpl_vars['GridHeadersNum']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/lister.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
<?php else: ?>
	<p><?php echo ((is_array($_tmp='srch_no_matches_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<?php endif; ?>