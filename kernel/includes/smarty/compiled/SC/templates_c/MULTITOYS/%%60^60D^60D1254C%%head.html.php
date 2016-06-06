<?php /* Smarty version 2.6.9, created on 2016-06-06 14:24:27
         compiled from head.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'head.html', 9, false),array('modifier', 'escape', 'head.html', 9, false),array('function', 'combine', 'head.html', 28, false),)), $this); ?>
<meta http-equiv="Content-Language" content="ru">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="shortcut icon" href="/favicon.ico">
<base href="<?php echo @CONF_FULL_SHOP_URL; ?>
">
<?php if ($this->_tpl_vars['rss_link']): ?>
<link rel="alternate" type="application/rss+xml" title="RSS 2.0" href="<?php echo @URL_ROOT; ?>
/<?php echo $this->_tpl_vars['rss_link']; ?>
">
<?php endif; ?>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('default', true, $_tmp, @CONF_DEFAULT_TITLE) : smarty_modifier_default($_tmp, @CONF_DEFAULT_TITLE)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</title>
<?php echo $this->_tpl_vars['page_meta_tags']; ?>

<?php if ($this->_tpl_vars['MSIE'] == 'MSIE'): ?>
<link href='http://fonts.googleapis.com/css?family=Dosis:500,600|PT+Sans:400,400italic|PT+Sans+Narrow:400,700|Ubuntu+Condensed|Open+Sans:400italic,400|Oxygen&subset=latin,cyrillic-ext' rel='stylesheet' type='text/css'>
<?php else: ?>
<?php echo '
<style>
    @font-face{font-family:\'Open Sans\';font-style:normal;font-weight:400;src:local(\'Open Sans\'),local(OpenSans),url(http://fonts.gstatic.com/s/opensans/v10/RjgO7rYTmqiVp7vzi-Q5UVtXRa8TVwTICgirnJhmVJw.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Open Sans\';font-style:italic;font-weight:400;src:local(\'Open Sans Italic\'),local(OpenSans-Italic),url(http://fonts.gstatic.com/s/opensans/v10/xjAJXh38I15wypJXxuGMBhLwxQbNcFnuWuPk3YnjToI.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Oxygen\';font-style:normal;font-weight:400;src:local(Oxygen),local(Oxygen-Regular),url(http://fonts.gstatic.com/s/oxygen/v5/JBQT4XXxNjmEkBx5N6608vesZW2xOQ-xsNqO47m55DA.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215,U+E0FF,U+EFFD,U+F000}@font-face{font-family:\'PT Sans\';font-style:normal;font-weight:400;src:local(\'PT Sans\'),local(PTSans-Regular),url(http://fonts.gstatic.com/s/ptsans/v8/vtwNVMP8y9C17vLvIBNZI_Y6323mHUZFJMgTvxaG2iE.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans\';font-style:italic;font-weight:400;src:local(\'PT Sans Italic\'),local(PTSans-Italic),url(http://fonts.gstatic.com/s/ptsans/v8/7dSh6BcuqDLzS2qAASIeuhJtnKITppOI_IvcXXDNrsc.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans Narrow\';font-style:normal;font-weight:400;src:local(\'PT Sans Narrow\'),local(PTSans-Narrow),url(http://fonts.gstatic.com/s/ptsansnarrow/v7/UyYrYy3ltEffJV9QueSi4Uvi3q9-zTdQoLrequQTguk.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans Narrow\';font-style:normal;font-weight:700;src:local(\'PT Sans Narrow Bold\'),local(PTSans-NarrowBold),url(http://fonts.gstatic.com/s/ptsansnarrow/v7/Q_pTky3Sc3ubRibGToTAYuICWD8dS1fawAsHP3zkW40.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Ubuntu Condensed\';font-style:normal;font-weight:400;src:local(\'Ubuntu Condensed\'),local(UbuntuCondensed-Regular),url(http://fonts.gstatic.com/s/ubuntucondensed/v6/DBCt-NXN57MTAFjitYxdrCGXDOnx9ptx3UTSPTrfsgk.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}
</style>
'; ?>

<?php endif; ?>
<script>
    var WAROOT_URL = '<?php echo @BASE_WA_URL; ?>
';
    var inLog = '<?php echo $_SESSION['log']; ?>
';
    var currensy = '<?php echo $_SESSION['current_currency']; ?>
';
    var usd = '<?php echo $_SESSION['curs']; ?>
';
</script>
<?php if ($this->_tpl_vars['PAGE_VIEW'] == 'mobile'): ?>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['css_m_main'],'output' => '/cache/m.main.css','age' => '5'), $this);?>

<script <?php echo ((is_array($_tmp=@$this->_tpl_vars['defer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src='/js/jquery-1.11.0.min.js'></script>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_m_multislidemenu'],'output' => '/cache/m.multislidemenu.js','age' => '3600','defer' => $this->_tpl_vars['defer']), $this);?>

<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_m_head'],'output' => '/cache/m.head.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php else: ?>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['css_main'],'output' => '/cache/main.css','age' => '5'), $this);?>

<script <?php echo ((is_array($_tmp=@$this->_tpl_vars['defer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src='/js/jquery-1.11.0.min.js'></script>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_multislidemenu'],'output' => '/cache/multislidemenu.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_baron'],'output' => '/cache/baron.js','age' => '3600','defer' => $this->_tpl_vars['defer']), $this);?>

<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_head'],'output' => '/cache/head.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php endif; ?>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_functions'],'output' => '/cache/functions.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php if ($_SESSION['log']): ?>
<?php if ($this->_tpl_vars['conc']): ?>
<link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css" media="screen"/>
<script <?php echo ((is_array($_tmp=@$this->_tpl_vars['defer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 type="text/javascript" src="/lib/jquery.mousewheel-3.0.6.pack.js"></script>
<?php if ($this->_tpl_vars['PAGE_VIEW'] == 'mobile'): ?>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_m_competitors'],'output' => '/cache/m.competitors.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php else: ?>
<?php echo smarty_function_combine(array('input' => $this->_tpl_vars['js_competitors'],'output' => '/cache/competitors.js','age' => '5','defer' => $this->_tpl_vars['defer']), $this);?>

<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php echo '
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
    ga(\'create\', \'UA-28553532-1\', {\'userId\': '; ?>
 '<?php echo $_SESSION['log']; ?>
' <?php echo '});
    ga(\'send\', \'pageview\');
</script>
'; ?>