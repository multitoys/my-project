<?php /* Smarty version 2.6.9, created on 2015-10-02 16:33:25
         compiled from addmodcurrency_toolbar.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('block', 'wbs_toolbarItem', 'addmodcurrency_toolbar.htm', 1, false),array('function', 'wbs_buttonSetButton', 'addmodcurrency_toolbar.htm', 2, false),)), $this); ?>
<?php $this->_tag_stack[] = array('wbs_toolbarItem', array()); smarty_block_wbs_toolbarItem($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>
	<?php echo smarty_function_wbs_buttonSetButton(array('name' => 'savebtn','caption' => $this->_tpl_vars['kernelStrings']['app_save_btn']), $this);?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_wbs_toolbarItem($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>

<?php $this->_tag_stack[] = array('wbs_toolbarItem', array()); smarty_block_wbs_toolbarItem($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>
	<?php echo smarty_function_wbs_buttonSetButton(array('name' => 'cancelbtn','caption' => $this->_tpl_vars['kernelStrings']['app_cancel_btn']), $this);?>

<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_wbs_toolbarItem($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>