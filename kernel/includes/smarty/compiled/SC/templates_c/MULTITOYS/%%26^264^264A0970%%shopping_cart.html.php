<?php /* Smarty version 2.6.9, created on 2015-11-18 17:33:35
         compiled from shopping_cart.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'shopping_cart.html', 33, false),array('modifier', 'set_query_html', 'shopping_cart.html', 36, false),array('modifier', 'escape', 'shopping_cart.html', 125, false),array('modifier', 'is_new_product', 'shopping_cart.html', 130, false),array('modifier', 'string_format', 'shopping_cart.html', 161, false),array('modifier', 'transcape', 'shopping_cart.html', 168, false),array('function', 'cycle', 'shopping_cart.html', 120, false),)), $this); ?>
<div id="blck-content">


<table cellpadding="0" cellspacing="0" width="100%">
<tr>
	<td <?php if ($this->_tpl_vars['PAGE_VIEW'] == 'noframe'): ?>class="background_cart_top"<?php endif; ?> id="cart_page_title">
		<h1 style='margin:0;'><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
		<div>
		<a id="my_closeLink" href='<?php echo ((is_array($_tmp="?ukey=home&view=frame")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' target="_parent" onclick="window.history.back()">
			<?php echo ((is_array($_tmp='goto_shopping')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		</a>
	</div>
		</td>
	</tr>
</table>

<div <?php if ($this->_tpl_vars['PAGE_VIEW'] == 'noframe'): ?>class="paddingblock"<?php endif; ?>>

<?php echo $this->_tpl_vars['MessageBlock']; ?>



<?php if ($this->_tpl_vars['cart_content']): ?>
	<?php if ($this->_tpl_vars['make_more_exact_cart_content']): ?>
	<p><?php echo ((is_array($_tmp='cart_items_left_from_prev_session')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	<?php endif; ?>
		<div class='error_block'><span class="error_flag" style="font-size: 150%; color: red;"><?php echo ((is_array($_tmp='cart_min_order_amount_not_reached')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <?php echo $this->_tpl_vars['cart_min']; ?>
</span></div>
	
	<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" name="ShoppingCartForm" method="post" target="_self">
	<input type="hidden" name="update" value="1" />
	<input type="hidden" name="shopping_cart" value="1" />
	<table id="cart_content_tbl" cellspacing="0">
    <colgroup>
        <col width="1%" />
        <col width="1%" />
        <col width="35%" />
        <col width="12%" />
        <col width="10%" />
        <col width="10%" />
		<col width="10%" />
        <col width="13%" />
        <col width="8%" />
    </colgroup>
	<tr id="cart_content_header">
		<td></td>
		<td></td>
		<td align="center">
            <a href="/?ukey=cart&sort=name&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>">
            <table><tr><td>Наименование товара</td><td>
                <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'name'): ?>z_sort_desc<?php else: ?>z_sort_asc<?php endif; ?>"></div>
            </td></tr></table>
        </a>
        </td>
		<td align="center"><a href="/?ukey=cart&sort=Price&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>">
            <table><tr><td>Цена</td><td>
                <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'Price'): ?>z_sort_desc<?php else: ?>z_sort_asc<?php endif; ?>"></div>
            </td></tr></table>
        </a></td>
        <td align="center">
            <table><tr><td>Остаток</td></tr></table>
        </td>
        <td align="center"><a href="/?ukey=cart&sort=count&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>">
            <table><tr><td><?php echo ((is_array($_tmp='cart_product_quantity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td><td>
                <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'count'): ?>z_sort_desc<?php else: ?>z_sort_asc<?php endif; ?>"></div>
            </td></tr></table>
        </a></td>
        <td align="center"><a href="/?ukey=cart&sort=Bonus&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>">
                <table>
                    <tr>
                        <td>Кол-во баллов</td>
                        <td>
                            <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'Bonus'): ?>z_sort_desc<?php else: ?>z_sort_asc<?php endif; ?>"></div>
                        </td>
                    </tr>
                </table>
            </a>
        </td>
        <td align="center">Сумма</td>
        <td align="center">Удалить</td>
	</tr>
	<?php $this->assign('ProductsNum', 0); ?>
	<?php $this->assign('num', 1); ?>
	<?php unset($this->_sections['i']);
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['cart_content']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['name'] = 'i';
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
' style='height:20px;'>
	  <td width="1%" align='right'>
     	<?php echo $this->_tpl_vars['num']++; ?>
)
    </td>
		<td align="center" valign="top" width="1%"><?php if ($this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['thumbnail_url']): ?>
       <img class='preview' data-pid='<?php echo ((is_array($_tmp=$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['picture_url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' src="<?php echo ((is_array($_tmp=$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['thumbnail_url'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="width:75px;" />
		   <?php else: ?>&nbsp;<?php endif; ?></td>
		<td>
			<?php if (! $this->_tpl_vars['widget_view']): ?>
			<a href='<?php echo ((is_array($_tmp="?ukey=product&productID=".($this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['productID'])."&product_slug=".($this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' <?php if ($this->_tpl_vars['PAGE_VIEW'] == 'noframe'): ?>class="gofromfade"<?php endif; ?>><?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['name']; ?>
 (арт.<?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['product_code']; ?>
)</a>
                <?php if (((is_array($_tmp=$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['productID'])) ? $this->_run_mod_handler('is_new_product', true, $_tmp) : smarty_modifier_is_new_product($_tmp)) > 0): ?>
                    &nbsp;&nbsp;<span style='color: white; background-color: red;'>&nbsp;Новинка!&nbsp;</span>
                <?php endif; ?>

			<?php else: ?>
			<?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['name']; ?>

                <?php if (((is_array($_tmp=$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['productID'])) ? $this->_run_mod_handler('is_new_product', true, $_tmp) : smarty_modifier_is_new_product($_tmp)) > 0): ?>
                    &nbsp;&nbsp;<span style='color: white; background-color: red;'>&nbsp;Новинка!&nbsp;</span>
                <?php endif; ?>
			<?php endif; ?>
		</td>
		<td align="center"><?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['costUC']; ?>
</td>
		<td align="center"><?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['ostatok']; ?>
&nbsp;шт.</td>
        <td align="center" style='white-space:nowrap;'>
			<?php $this->assign('ProductsNum', $this->_tpl_vars['ProductsNum']+$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['quantity']); ?>

			<?php if ($this->_tpl_vars['session_items']):  $this->assign('_prdid', $this->_tpl_vars['session_items'][$this->_sections['i']['index']]); ?>
			<?php else:  $this->assign('_prdid', $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['id']); ?>
			<?php endif; ?>

			<input class="cart_product_quantity digit" type="text" maxlength="3" name="count_<?php echo $this->_tpl_vars['_prdid']; ?>
" value="<?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['quantity']; ?>
" size="3" />

			<?php if ($this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['min_order_amount']): ?>
			<div class="error_block"><span class="error_msg" style="font-size:150%; color: red;">
				<?php echo ((is_array($_tmp='prdset_minimal_order_quantity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['min_order_amount']; ?>

				<?php echo ((is_array($_tmp='str_items')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			</span></div>
			<?php endif; ?>
		</td>
        <td align="center">
            <?php echo ((is_array($_tmp=$this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['Bonus'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
&nbsp;бал.
        </td>
        <td align="center" nowrap="nowrap">
			<?php echo $this->_tpl_vars['cart_content'][$this->_sections['i']['index']]['cost']; ?>

		</td>
		<td align="center">
			<input type="checkbox" class='del_chkbtn' name='del_<?php echo $this->_tpl_vars['_prdid']; ?>
' data-pid="<?php echo $this->_tpl_vars['_prdid']; ?>
"/>
			<a href='<?php echo ((is_array($_tmp="remove=".($this->_tpl_vars['_prdid']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
'>
			<img src="<?php echo @URL_IMAGES; ?>
/remove.gif" alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
' style='color:white;'/>
			</a>
		</td>
	</tr>
	<?php endfor; endif; ?>

    <?php if ($this->_tpl_vars['cart_discount'] != ''): ?>
    <tr>
        <td colspan="3" class="cart_discount_label">
            <?php echo ((is_array($_tmp='usr_custinfo_group_discount')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
,&nbsp;<?php echo ((is_array($_tmp=$this->_tpl_vars['discount_percent'])) ? $this->_run_mod_handler('string_format', true, $_tmp, '%0.1f%%') : smarty_modifier_string_format($_tmp, '%0.1f%%')); ?>

        </td>
        <td align="center" nowrap="nowrap">
            - <span id="discount_value"><?php echo $this->_tpl_vars['cart_discount']; ?>
</span>
        </td>
        <td></td>
    </tr>
    <?php endif; ?>

    <?php if (@CONF_DSC_COUPONS_ENABLED == 'Y'): ?>
    	<tr id="coupon_form" style="display: <?php if ($this->_tpl_vars['current_coupon'] != '0'): ?>none<?php endif; ?>;">
    		<td colspan="3" class="cart_discount_label">
    			<?php echo ((is_array($_tmp='lbl_frnt_discount_coupon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
                <input type="text" size="12" maxlength="10" name="discount_coupon_code" id="discount_coupon_code" value="" onBlur="onApplyButtonClick();" onkeypress="return noenter(event);" />
                <button type="button" onClick="onApplyButtonClick();"  tabindex="1001"><?php echo ((is_array($_tmp='btn_apply')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</button>
    		</td>
    		<td align="center">
                <span id="wrong_coupon_lbl" style="color: #666666; font-size: 80%; display: none;"><?php echo ((is_array($_tmp='lbl_wrong_coupon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
                <b id="processing_coupon_lbl" style="color: blue; display: none;"><?php echo ((is_array($_tmp='lbl_processing_coupon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</b>
            </td>
    	</tr>
        <tr id="coupon_info" style="display: <?php if ($this->_tpl_vars['current_coupon'] == '0'): ?>none<?php endif; ?>;">
            <td colspan="3" class="cart_discount_label">
                <?php echo ((is_array($_tmp='lbl_frnt_discount_coupon')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
                <b id="coupon_info_code"><?php echo $this->_tpl_vars['current_coupon']; ?>
</b>
                <button type="button" onClick="onDeleteCouponClick();" tabindex="1002"><?php echo ((is_array($_tmp='btn_change')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</button>
            </td>
            <td align="center">
                - <span id="coupon_discount_value"><?php echo $this->_tpl_vars['coupon_discount']; ?>
</span>
            </td>
        </tr>
    <?php endif; ?>

    <?php if ($this->_tpl_vars['cart_discount'] == '' && @CONF_DSC_COUPONS_ENABLED == 'N'): ?>
    <tr style="height: 30px;"></tr>
    <?php endif; ?>

	<tr>
        <td></td>
        <td id="cart_total_label" colspan="4">
            <input type='hidden' name="123" id="recalculate" value='123'>
            <input type="button" class="blue-button check" value='Обновить заказ' tabindex="1004"
                   onclick='del_items();'>
            <input type="submit" class="btn_checkout blue-button check" name="checkout"
                   value="<?php echo ((is_array($_tmp='str_checkout')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" id="btn-checkout" tabindex="1005">
        </td>
        <td style="color:blue; font-size: 130%;"><b><?php echo ((is_array($_tmp='str_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</b></td>
        <td style="color:green;"><b><?php echo $this->_tpl_vars['bonus_total']; ?>
</b>&nbsp;баллов</td>
        <td id="cart_total" style="color:red; font-size: 110%;"><b><?php echo $this->_tpl_vars['cart_total']; ?>
</b></td>
		<td align='right'></td>
	</tr>
	
<tr style="height: 10px;"></tr>
                                                        
                                                                                        	</table>
	</form>
</div>
<?php else: ?>
<h3><?php echo ((is_array($_tmp='cart_cart_is_empty')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h3>
<?php endif; ?>
<br>
  </div>

