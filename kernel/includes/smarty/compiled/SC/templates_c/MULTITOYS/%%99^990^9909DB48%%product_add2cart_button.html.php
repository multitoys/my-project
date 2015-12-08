<?php /* Smarty version 2.6.9, created on 2015-12-08 19:22:17
         compiled from product_add2cart_button.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'z_shopping_count', 'product_add2cart_button.html', 14, false),array('modifier', 'translate', 'product_add2cart_button.html', 35, false),)), $this); ?>
<?php if (! $this->_tpl_vars['printable_version']): ?>
	<?php if ($this->_tpl_vars['product_info']['ordering_available'] && $this->_tpl_vars['product_info']['Price'] > 0 && ( @CONF_SHOW_ADD2CART == 1 ) && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['product_info']['in_stock'] > 0 )): ?>
        <table width='125px'>
            <tbody>
                <tr>
                    <td>
                        <input name="product_qty" id='qty<?php echo $this->_tpl_vars['product_info']['productID']; ?>
' class="cart_product_quantity" data-id='<?php echo $this->_tpl_vars['product_info']['productID']; ?>
' type="text" size="2" value="" title='Количество'
                        <?php echo '
                        onkeypress=\'if (event.keyCode == 13){add_2cart("#qty';  echo $this->_tpl_vars['product_info']['productID'];  echo '");}\'>
                        '; ?>

                        <button class="z_add_cart" style="margin-left:10px;" onclick='add_2cart("#qty<?php echo $this->_tpl_vars['product_info']['productID']; ?>
");' type="button">
                            <div id="zpid_<?php echo $this->_tpl_vars['product_info']['productID']; ?>
" class='in_cart'>
                                <div class="animated zoomInDown">
                                    <?php echo smarty_function_z_shopping_count(array('productID' => $this->_tpl_vars['product_info']['productID']), $this);?>

                                </div>
                            </div>
                        </button>
                    </td>
                                                                                                                                                                                                                                                                                                                                        </tr>
            </tbody>
        </table>
	<?php elseif (@CONF_SHOW_ADD2CART == 1 && @CONF_CHECKSTOCK && ! $this->_tpl_vars['product_info']['in_stock'] && $this->_tpl_vars['product_info']['ordering_available']): ?>
        <div class="prd_out_of_stock"><?php echo ((is_array($_tmp='prd_out_of_stock')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
	<?php endif;  endif; ?>