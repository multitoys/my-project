<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:31
         compiled from backend/product_tree.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/product_tree.tpl.html', 21, false),array('modifier', 'escape', 'backend/product_tree.tpl.html', 59, false),array('function', 'count', 'backend/product_tree.tpl.html', 43, false),array('function', 'counter', 'backend/product_tree.tpl.html', 98, false),array('function', 'cycle', 'backend/product_tree.tpl.html', 100, false),)), $this); ?>
<?php echo '
<script language="javascript" type="text/javascript">
<!--
function checkAllProducts(_ProdNum){
	var checkbox;
	var group_checkbox = document.getElementById(\'id_checkallprod\');
	if(group_checkbox)
	for(var i=1; i<=_ProdNum; i++){
		if(checkbox = document.getElementById(\'prod\'+i))
		checkbox.checked = group_checkbox.checked;
	}
}
//-->
</script>
'; ?>

<input name="expandID" type="hidden" />
<input name="unexpandID" type="hidden" />
<input name="showProducts" type="hidden" />
<input name="updateCategory" value="" type="hidden" />
<input name="clear_session" value="<?php echo ((is_array($_tmp='btn_reset')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="submit" />
<table>
<?php $_from = $this->_tpl_vars['ProductCategories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_ProductCategory']):
?>
	<tr>
		<td style="padding-left:<?php echo $this->_tpl_vars['_ProductCategory']['level']*20; ?>
">
			<?php if ($this->_tpl_vars['_ProductCategory']['ExistSubCategories']): ?>
				<?php if ($this->_tpl_vars['_ProductCategory']['ExpandedCategory']): ?>
				<a href="javascript:void(0)"
					onclick="
							document.form_export.elements['unexpandID'].value = <?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
;
							document.form_export.submit();
							return false;"><img src="images_common/minus.gif"  border="0" /></a>
				<?php else: ?>
				<a href="javascript:void(0)"
					onclick="document.form_export.elements['expandID'].value = <?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
;
							document.form_export.submit();
							return false;"><img src="images_common/plus.gif"  border="0" /></a>
				<?php endif; ?>
			<?php else: ?>
				<img src="images_common/plus.gif"  border="0" style="visibility:hidden;" />
			<?php endif; ?>
			
			<?php echo smarty_function_count(array('array' => $this->_tpl_vars['session_selectedProducts'][$this->_tpl_vars['_ProductCategory']['categoryID']],'item' => '_ProductsNum'), $this);?>

			
			<input name="CHECKED_CATEGORIES[<?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
]" 
						type="checkbox" class="checknomarging" value="1"
						onclick="
							document.form_export.elements['updateCategory'].value = <?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
;
							document.form_export.submit();
							return false;"
						id="category<?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
"
						<?php if ($this->_tpl_vars['session_checkedCategories'][$this->_tpl_vars['_ProductCategory']['categoryID']] || $this->_tpl_vars['_ProductsNum'] || $this->_tpl_vars['session_selectedProductsIncSub'][$this->_tpl_vars['_ProductCategory']['categoryID']]): ?> checked="checked"<?php endif; ?> />
			<?php if ($this->_tpl_vars['_ProductCategory']['products_count_category']): ?>
			<a class="standard" href="javascript:void(0)" 
				onclick="
					document.form_export.elements['showProducts'].value = <?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
;
					document.form_export.submit();
					return false;
					"><?php echo ((is_array($_tmp=$this->_tpl_vars['_ProductCategory']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a> 
			<?php else: ?>
				<label for="<?php echo $this->_tpl_vars['_ProductCategory']['categoryID']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['_ProductCategory']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</label>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['_ProductCategory']['ExpandedCategory'] || ! $this->_tpl_vars['_ProductCategory']['ExistSubCategories']): ?>
				(
				<?php if ($this->_tpl_vars['_ProductsNum']): ?>
					<?php echo $this->_tpl_vars['_ProductsNum']; ?>
 
				<?php elseif ($this->_tpl_vars['session_checkedCategories'][$this->_tpl_vars['_ProductCategory']['categoryID']]): ?>
					<?php echo $this->_tpl_vars['_ProductCategory']['products_count_category']; ?>

				<?php else: ?>
				0
				<?php endif; ?>
				/
				<?php echo $this->_tpl_vars['_ProductCategory']['products_count_category']; ?>

				)
			<?php else: ?>
				(
				<?php if ($this->_tpl_vars['session_selectedProductsIncSub'][$this->_tpl_vars['_ProductCategory']['categoryID']]): ?>
					<?php echo $this->_tpl_vars['session_selectedProductsIncSub'][$this->_tpl_vars['_ProductCategory']['categoryID']]; ?>
 
				<?php elseif ($this->_tpl_vars['session_selectedProductsIncSub'][$this->_tpl_vars['_ProductCategory']['categoryID']]): ?>
					<?php echo $this->_tpl_vars['_ProductCategory']['products_count']; ?>

				<?php else: ?>0
				<?php endif; ?>
				/
				<?php echo $this->_tpl_vars['_ProductCategory']['products_count']; ?>

				)
			<?php endif; ?>
			<?php if ($this->_tpl_vars['showProducts'] == $this->_tpl_vars['_ProductCategory']['categoryID'] && $this->_tpl_vars['ProductsNum']): ?>
			<br /><br />
			<input name="cIDForProducts" value="<?php echo $this->_tpl_vars['showProducts']; ?>
" type="hidden" />
			<table class="grid" style="margin-left:20px;">
			<tr class="gridsheader">
				<td><input type="checkbox" onclick="checkAllProducts(<?php echo $this->_tpl_vars['ProductsNum']; ?>
)" id="id_checkallprod"
				<?php if ($this->_tpl_vars['session_checkedCategories'][$this->_tpl_vars['_ProductCategory']['categoryID']]): ?>checked="checked"<?php endif; ?>
				 /></td>
				<td><?php echo ((is_array($_tmp='prdset_product_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
				<td><?php echo ((is_array($_tmp='str_price')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
,&nbsp;<?php echo ((is_array($_tmp='str_universal_currency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			</tr>
			<?php echo smarty_function_counter(array('start' => 0,'skip' => 1,'print' => false), $this);?>

			<?php $_from = $this->_tpl_vars['Products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_Product']):
?>
			<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
				<td><input name="PRODUCTS[<?php echo $this->_tpl_vars['_Product']['productID']; ?>
]" value="1" id="prod<?php echo smarty_function_counter(array(), $this);?>
" type="checkbox"
						<?php if ($this->_tpl_vars['session_selectedProducts'][$this->_tpl_vars['showProducts']][$this->_tpl_vars['_Product']['productID']]): ?>checked="checked"<?php endif; ?> /></td>
				<td><label for="prod<?php echo smarty_function_counter(array(), $this);?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['_Product']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</label></td>
				<td align="right">
				<?php if ($this->_tpl_vars['_Product']['Price']): ?>
					<?php echo $this->_tpl_vars['_Product']['Price']; ?>

				<?php else: ?>
					<?php echo ((is_array($_tmp='msg_price_isnot_set')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

				<?php endif; ?>
					</td>
			</tr>
			<?php endforeach; endif; unset($_from); ?>
			<tr class="gridsfooter">
				<td colspan="3"><input name="save_products" value="<?php echo ((is_array($_tmp='btn_select')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="submit" /></td>
			</tr>
			</table>
			<?php endif; ?>
		</td>
	</tr>
<?php endforeach; endif; unset($_from); ?>
</table>