<?php /* Smarty version 2.6.9, created on 2015-10-22 11:48:52
         compiled from product_description.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'product_description.html', 5, false),)), $this); ?>
<div>
<?php if (! $this->_tpl_vars['CPT_CONSTRUCTOR_MODE']): ?>
	<?php echo $this->_tpl_vars['product_info']['description']; ?>

<?php else: ?>
	<?php echo ((is_array($_tmp='demoprd_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php endif; ?>
</div>