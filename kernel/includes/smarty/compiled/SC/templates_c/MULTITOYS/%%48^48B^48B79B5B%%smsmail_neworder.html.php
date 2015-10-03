<?php /* Smarty version 2.6.9, created on 2015-09-22 08:32:53
         compiled from backend/smsmail_neworder.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/smsmail_neworder.html', 1, false),)), $this); ?>
<?php echo ((is_array($_tmp='sms_message_new_order')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php echo ((is_array($_tmp='ordr_id')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['OrderNumber']; ?>

<?php echo ((is_array($_tmp='ordr_order_total')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo $this->_tpl_vars['OrderAmount']; ?>