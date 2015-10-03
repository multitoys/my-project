<?php /* Smarty version 2.6.9, created on 2015-09-21 23:56:08
         compiled from news.frontend.list.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'cs_show_news_menu', 'news.frontend.list.tpl.html', 2, false),)), $this); ?>
<h1>Новости<?php if ($this->_tpl_vars['rss_link']): ?>&nbsp;<a href="<?php echo @URL_ROOT; ?>
/<?php echo $this->_tpl_vars['rss_link']; ?>
"><img src="<?php echo @URL_IMAGES_COMMON; ?>
/rss-feed.png" alt="RSS 2.0"  style="padding-left:10px;"></a><?php endif; ?></h1>
<?php echo smarty_function_cs_show_news_menu(array(), $this);?>