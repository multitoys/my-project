<?php /* Smarty version 2.6.9, created on 2015-09-21 23:17:29
         compiled from product_name.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'product_name.html', 3, false),array('modifier', 'translate', 'product_name.html', 5, false),)), $this); ?>
<?php if (! $this->_tpl_vars['CPT_CONSTRUCTOR_MODE']): ?>

	<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</h1>
	<?php if ($this->_tpl_vars['product_info']['eproduct_filename'] != ""): ?>
		<?php echo ((is_array($_tmp='prdset_product_is_downloadable_msg_2customer')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<b><?php echo $this->_tpl_vars['product_info']['eproduct_filesize_str']; ?>
</b>)
	<?php endif;  else: ?>
	<h1><?php echo ((is_array($_tmp='demoprd')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
<?php endif; ?>