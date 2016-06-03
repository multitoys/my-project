<?php /* Smarty version 2.6.9, created on 2016-06-03 09:58:00
         compiled from frame.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'component', 'frame.html', 3, false),)), $this); ?>
<?php if ($this->_tpl_vars['ajax']): ?>
    <?php if ($_SESSION['log'] && $_SESSION['cs_may_order']): ?>
        <?php echo smarty_function_component(array('cpt_id' => 'maincontent'), $this);?>

    <?php else: ?>
        <script>window.location.reload(true);</script>
    <?php endif;  else: ?>
<!DOCTYPE html>
<html>
<head>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</head>
<body id="qwe" <?php if ($this->_tpl_vars['main_body_style']): ?> <?php echo $this->_tpl_vars['main_body_style'];  endif;  if ($this->_tpl_vars['PAGE_VIEW'] == 'printable'): ?> style="background-color:#FFFFFF;background-image:none;"<?php endif;  if ($this->_tpl_vars['page_not_found404']): ?> class="body-page-404"<?php endif; ?>>
<?php if ($this->_tpl_vars['page_not_found404']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "404.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['market_link']): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "market.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  elseif ($this->_tpl_vars['PAGE_VIEW'] == 'mobile'): ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "m.index.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  else: ?>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "index.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  endif; ?>
</body>
</html>
<?php endif; ?>