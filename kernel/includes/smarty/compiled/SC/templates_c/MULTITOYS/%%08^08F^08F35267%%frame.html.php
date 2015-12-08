<?php /* Smarty version 2.6.9, created on 2015-12-08 17:54:34
         compiled from frame.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'frame.html', 28, false),)), $this); ?>
<?php echo '';  echo '<!DOCTYPE html>';  echo '';  echo '<html><head><meta http-equiv="Content-Language" content="ru"><link rel="shortcut icon" href="/favicon.ico"><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><base href="';  echo @CONF_FULL_SHOP_URL;  echo '">';  if ($this->_tpl_vars['rss_link']):  echo '	<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="';  echo @URL_ROOT;  echo '/';  echo $this->_tpl_vars['rss_link'];  echo '">';  endif;  echo '<!-- Head start -->';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "head.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '<!-- Head end -->';  if ($_SESSION['log']):  echo '<script>';  echo '';  echo 'var WAROOT_URL = \'';  echo @BASE_WA_URL;  echo '\';';  echo '';  echo '</script>';  echo '';  echo '';  if (! $this->_tpl_vars['page_not_found404']):  echo '';  echo '<script ';  echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' '));  echo ' src="';  echo @URL_JS;  echo '/functions-1437581122255.js"></script>';  echo '';  echo '';  if ($_GET['step'] == 'shipping'):  echo '';  echo '';  endif;  echo '';  echo '';  endif;  echo '';  endif;  echo '';  echo '
    <script>
        (function (i, s, o, g, r, a, m) {
            i[\'GoogleAnalyticsObject\'] = r;
            i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
            a = s.createElement(o),
                    m = s.getElementsByTagName(o)[0];
            a.async = 1;
            a.src = g;
            m.parentNode.insertBefore(a, m)
        })(window, document, \'script\', \'//www.google-analytics.com/analytics.js\', \'ga\');
        ga(\'create\', \'UA-28553532-1\', {\'userId\': ';  echo ' \'';  echo $_SESSION['log'];  echo '\' ';  echo '});
        ga(\'send\', \'pageview\');
    </script>
    ';  echo '</head><body  id="qwe" ';  echo '';  if ($this->_tpl_vars['main_body_style']):  echo ' ';  echo $this->_tpl_vars['main_body_style'];  echo '';  endif;  echo '';  if ($this->_tpl_vars['PAGE_VIEW'] == 'printable'):  echo ' style="background-color:#FFFFFF;background-image:none;"';  endif;  echo '';  if ($this->_tpl_vars['page_not_found404']):  echo ' class="body-page-404"';  endif;  echo '><!--  BODY -->';  if ($this->_tpl_vars['main_body_tpl']):  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['main_body_tpl'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  else:  echo '';  if ($this->_tpl_vars['page_not_found404']):  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "404.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  else:  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "index.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  endif;  echo '';  endif;  echo '';  echo '<!--  END -->';  echo '</body></html>'; ?>