<?php /* Smarty version 2.6.9, created on 2015-12-08 18:55:14
         compiled from 404.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '404.html', 3, false),array('modifier', 'escape', '404.html', 4, false),)), $this); ?>
<div id="all-container" style="padding:0px 20px;">
	<h1 style="font-size: 200%; margin-top: 10px;">
	<font style="background-color: #ddeeff;font-size: 150%; padding: 20px 10px 5px 10px;">404</font> &mdash; <?php echo ((is_array($_tmp='lbl_not_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
	<h4><?php echo ((is_array($_tmp='err_cant_find_required_page')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <span><?php echo ((is_array($_tmp=$this->_tpl_vars['link404'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</span></h4>
	<p><?php echo ((is_array($_tmp='lbl_follow_link')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	<ul>
<li style="padding-bottom: 5px;"><a href="<?php echo @CONF_FULL_SHOP_URL; ?>
" style="font-weight: bold; font-size: 110%"><?php echo @CONF_SHOP_NAME; ?>
</a> <span style="color: #999; font-weight: bold"> — <?php echo ((is_array($_tmp='pgn_mainpage')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></li>
</ul>
</div>