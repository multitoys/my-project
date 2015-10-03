<?php /* Smarty version 2.6.9, created on 2015-09-22 08:32:53
         compiled from checkout.success.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cs_send_email', 'checkout.success.html', 3, false),)), $this); ?>
<?php echo $this->_tpl_vars['GOOGLE_ANALYTICS_ECOMMERCE_FORM']; ?>

<?php echo smarty_function_cs_send_email(array(), $this);?>

<?php if ($this->_tpl_vars['after_processing_html']): ?><p><?php echo $this->_tpl_vars['after_processing_html']; ?>
</p><?php endif; ?>