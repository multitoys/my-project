<?php /* Smarty version 2.6.9, created on 2016-01-29 11:44:25
         compiled from head.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'default', 'head.html', 3, false),array('modifier', 'escape', 'head.html', 3, false),)), $this); ?>
<title><?php echo ((is_array($_tmp=((is_array($_tmp=@$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('default', true, $_tmp, @CONF_DEFAULT_TITLE) : smarty_modifier_default($_tmp, @CONF_DEFAULT_TITLE)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</title>
<?php echo $this->_tpl_vars['page_meta_tags']; ?>

<?php echo '
    <style>
        @font-face{font-family:\'Open Sans\';font-style:normal;font-weight:400;src:local(\'Open Sans\'),local(OpenSans),url(http://fonts.gstatic.com/s/opensans/v10/RjgO7rYTmqiVp7vzi-Q5UVtXRa8TVwTICgirnJhmVJw.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Open Sans\';font-style:italic;font-weight:400;src:local(\'Open Sans Italic\'),local(OpenSans-Italic),url(http://fonts.gstatic.com/s/opensans/v10/xjAJXh38I15wypJXxuGMBhLwxQbNcFnuWuPk3YnjToI.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Oxygen\';font-style:normal;font-weight:400;src:local(Oxygen),local(Oxygen-Regular),url(http://fonts.gstatic.com/s/oxygen/v5/JBQT4XXxNjmEkBx5N6608vesZW2xOQ-xsNqO47m55DA.woff2) format("woff2");unicode-range:U+0000-00FF,U+0131,U+0152-0153,U+02C6,U+02DA,U+02DC,U+2000-206F,U+2074,U+20AC,U+2212,U+2215,U+E0FF,U+EFFD,U+F000}@font-face{font-family:\'PT Sans\';font-style:normal;font-weight:400;src:local(\'PT Sans\'),local(PTSans-Regular),url(http://fonts.gstatic.com/s/ptsans/v8/vtwNVMP8y9C17vLvIBNZI_Y6323mHUZFJMgTvxaG2iE.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans\';font-style:italic;font-weight:400;src:local(\'PT Sans Italic\'),local(PTSans-Italic),url(http://fonts.gstatic.com/s/ptsans/v8/7dSh6BcuqDLzS2qAASIeuhJtnKITppOI_IvcXXDNrsc.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans Narrow\';font-style:normal;font-weight:400;src:local(\'PT Sans Narrow\'),local(PTSans-Narrow),url(http://fonts.gstatic.com/s/ptsansnarrow/v7/UyYrYy3ltEffJV9QueSi4Uvi3q9-zTdQoLrequQTguk.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'PT Sans Narrow\';font-style:normal;font-weight:700;src:local(\'PT Sans Narrow Bold\'),local(PTSans-NarrowBold),url(http://fonts.gstatic.com/s/ptsansnarrow/v7/Q_pTky3Sc3ubRibGToTAYuICWD8dS1fawAsHP3zkW40.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}@font-face{font-family:\'Ubuntu Condensed\';font-style:normal;font-weight:400;src:local(\'Ubuntu Condensed\'),local(UbuntuCondensed-Regular),url(http://fonts.gstatic.com/s/ubuntucondensed/v6/DBCt-NXN57MTAFjitYxdrCGXDOnx9ptx3UTSPTrfsgk.woff2) format("woff2");unicode-range:U+0400-045F,U+0490-0491,U+04B0-04B1,U+2116}
    </style>
'; ?>

<link rel="stylesheet" href="<?php echo $this->_tpl_vars['URL_THEME_OFFSET']; ?>
/main.css" type="text/css">
    <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src='<?php echo @URL_JS; ?>
/jquery.1.9.1.js'></script>
<?php if ($this->_tpl_vars['ios'] != 'ios'): ?>
    <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src='<?php echo @URL_JS; ?>
/jquery.nicescroll.min-1437563245003.js'></script>
<?php endif;  if ($_SESSION['log']): ?>
    <?php if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'): ?>
        <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 type="text/javascript" src="/lib/jquery.mousewheel-3.0.6.pack.js"></script>
        <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 type="text/javascript" src="/source/jquery.fancybox.pack-1453308851238.js"></script>
        <link rel="stylesheet" type="text/css" href="/source/jquery.fancybox.css?v=2.1.5" media="screen"/>
    <?php endif;  endif;  if ($this->_tpl_vars['ios'] != 'ios'): ?>
    <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src="<?php echo $this->_tpl_vars['URL_THEME_OFFSET']; ?>
/head-1453123179214.js"></script>
<?php else: ?>
    <script <?php echo ((is_array($_tmp=@$this->_tpl_vars['deffer'])) ? $this->_run_mod_handler('default', true, $_tmp, ' ') : smarty_modifier_default($_tmp, ' ')); ?>
 src="<?php echo $this->_tpl_vars['URL_THEME_OFFSET']; ?>
/head-m-1453123179214.js"></script>
<?php endif; ?>