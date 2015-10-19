<?php /* Smarty version 2.6.9, created on 2015-10-19 12:06:37
         compiled from product_price.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'product_price.html', 17, false),array('modifier', 'escape', 'product_price.html', 35, false),)), $this); ?>
<?php if (! $this->_tpl_vars['CPT_CONSTRUCTOR_MODE']): ?>
	<?php if ($this->_tpl_vars['currencies_count'] != 0 && $this->_tpl_vars['product_info']['Price'] > 0): ?>
				<?php if ($this->_tpl_vars['product_info']['list_price'] > 0 && $this->_tpl_vars['product_info']['list_price'] > $this->_tpl_vars['product_info']['Price'] && $this->_tpl_vars['product_info']['Price'] > 0): ?>
        Старая цена: 
		<span class="regularPrice"><?php echo $this->_tpl_vars['product_info']['list_priceWithUnit']; ?>
</span>
        <div class="my_totalPrice">Новая цена: 
		<?php else: ?>
		<div class="my_totalPrice">Цена: 
        <?php endif; ?>
        <span class="productPrice"><?php echo $this->_tpl_vars['product_info']['PriceWithUnit']; ?>
</span>
        </div>

				<?php if ($this->_tpl_vars['product_info']['list_price'] > 0 && $this->_tpl_vars['product_info']['list_price'] > $this->_tpl_vars['product_info']['Price'] && $this->_tpl_vars['product_info']['Price'] > 0): ?>
		<div>
			<span class="youSaveLabel"><?php echo ((is_array($_tmp='str_you_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</span>
			<span class="youSavePrice"><?php echo $this->_tpl_vars['product_info'][14]; ?>
 (<?php echo $this->_tpl_vars['product_info'][15]; ?>
%)</span>
		</div>
		<?php endif; ?>
	<?php endif; ?>

        <?php if ($this->_tpl_vars['product_info']['Bonus'] > 0): ?>
	<div>
        <span class="productCodeLabel">Бонусы:&nbsp;</span>
        <span class="totalPrice"><?php echo $this->_tpl_vars['product_info']['Bonus']; ?>
&nbsp;баллов</span>
	</div>
	<?php endif; ?>

        <?php if ($this->_tpl_vars['product_info']['product_code'] && @CONF_ENABLE_PRODUCT_SKU): ?>
        <div>
            <span class="productCodeLabel"><?php echo ((is_array($_tmp='prdset_product_code')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:&nbsp;</span>
            <span class="productCode"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_info']['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</span>
        </div>
    <?php endif; ?>

	<div>
		<span class="productCodeLabel">На остатке:&nbsp;</span>
		<span class="productCode"><?php echo ((is_array($_tmp=$this->_tpl_vars['product_info']['ostatok'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&nbsp;шт.</span>
	</div>

		<?php if (@CONF_CHECKSTOCK == '1'): ?>
		<?php if ($this->_tpl_vars['product_info']['in_stock'] > 0): ?>
		<div>
			<?php echo ((is_array($_tmp='str_in_stock')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
			<?php if (@CONF_EXACT_PRODUCT_BALANCE): ?>
				<?php echo $this->_tpl_vars['product_info']['in_stock']; ?>

			<?php else: ?>
				<?php echo ((is_array($_tmp='str_answer_yes')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

			<?php endif; ?>
		</div>
		<?php endif; ?>
	<?php endif; ?>


	<?php if ($this->_tpl_vars['product_info']['shipping_freightUC']): ?>
	<div>
		<?php echo ((is_array($_tmp='prdset_handling_charge')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:&nbsp;
			<font color=brown><?php echo $this->_tpl_vars['product_info']['shipping_freightUC']; ?>
</font>
	</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['product_info']['min_order_amount'] > 1): ?>
	<div>
		<?php echo ((is_array($_tmp='prdset_minimal_order_quantity')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['product_info']['min_order_amount']; ?>

			<?php echo ((is_array($_tmp='str_items')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	</div>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['product_info']['weight'] > 0): ?>
	<div>
		<?php echo ((is_array($_tmp='prdset_weight')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['product_info']['weight']; ?>
 <?php echo @CONF_WEIGHT_UNIT; ?>

	</div>
	<?php endif;  else: ?>
	<span class="regularPrice">$100</span>
	<span class="totalPrice">$90</span>

	<div>
		<span class="youSaveLabel"><?php echo ((is_array($_tmp='str_you_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</span>
		<span class="youSavePrice">$10 (10%)</span>
	</div>
	<?php if (@CONF_ENABLE_PRODUCT_SKU): ?>
	<div>
		<span class="productCodeLabel"><?php echo ((is_array($_tmp='prdset_product_code')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:&nbsp;</span>
		<span class="productCode">ART2800</span>
	</div>
	<?php endif;  endif; ?>