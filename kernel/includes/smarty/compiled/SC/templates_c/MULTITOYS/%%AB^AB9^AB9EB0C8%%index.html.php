<?php /* Smarty version 2.6.9, created on 2015-09-22 08:33:05
         compiled from backend/index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/index.html', 11, false),array('modifier', 'escape', 'backend/index.html', 17, false),)), $this); ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<link rel="stylesheet" href="<?php echo @URL_CSS; ?>
/admin.css" type="text/css" />
		<!--[if IE]><link rel="stylesheet" href="<?php echo @URL_CSS; ?>
/ie.admin.css" type="text/css" /><![endif]-->
		<script type="text/javascript" src="<?php echo @URL_JS; ?>
/functions.js"></script>
		<script type="text/javascript" src="<?php echo @URL_JS; ?>
/behavior.js"></script>
		<script type="text/javascript" src="<?php echo @URL_JS; ?>
/admin.js"></script>
		
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<title><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 - <?php echo ((is_array($_tmp='str_admin_title')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</title>
		<script type="text/javascript">
		<?php if (@CONF_WAROOT_URL): ?>var WAROOT_URL = '<?php echo @CONF_WAROOT_URL; ?>
';<?php endif; ?>
		<?php if (@CONF_ON_WEBASYST): ?>var CONF_ON_WEBASYST = '<?php echo @CONF_ON_WEBASYST; ?>
';<?php endif; ?>
		window.url_img = '<?php echo @URL_IMAGES; ?>
';
		var translate = {
			'msg_unsaved_changes': '<?php echo ((is_array($_tmp=((is_array($_tmp='msg_unsaved_changes')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
',
			'btn_close': '<?php echo ((is_array($_tmp=((is_array($_tmp='btn_close')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'
			};
		var url_current_theme_css = '';//'<?php echo $this->_tpl_vars['url_current_theme_css']; ?>
';
		</script>
	</head>

	<body>
	<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['Warnings']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
		<?php echo $this->_tpl_vars['Warnings'][$this->_sections['j']['index']]; ?>

	<?php endfor; endif; ?>
			<?php if ($this->_tpl_vars['admin_main_content_template'] != ''): ?>
			<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/".($this->_tpl_vars['admin_main_content_template']), 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
			<?php endif; ?>
	</body>
</html>