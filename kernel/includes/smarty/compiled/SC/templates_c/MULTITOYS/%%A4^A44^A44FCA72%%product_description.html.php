<?php /* Smarty version 2.6.9, created on 2015-12-10 11:57:01
         compiled from product_description.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'product_description.html', 8, false),)), $this); ?>
<div>
<?php if (! $this->_tpl_vars['CPT_CONSTRUCTOR_MODE']): ?>
	<?php if ($this->_tpl_vars['product_info']['description']): ?>
        <h3 style="text-align: left">Описание:</h3>
        <?php echo $this->_tpl_vars['product_info']['description']; ?>

    <?php endif; ?>
<?php else: ?>
	<?php echo ((is_array($_tmp='demoprd_description')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php endif; ?>
</div>