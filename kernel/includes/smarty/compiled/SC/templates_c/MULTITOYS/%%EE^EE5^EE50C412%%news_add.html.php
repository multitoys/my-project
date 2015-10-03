<?php /* Smarty version 2.6.9, created on 2015-09-27 18:25:57
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/news_add.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/news_add.html', 2, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/news_add.html', 2, false),)), $this); ?>
<h1 class="breadcrumbs">
	<a href="<?php echo ((is_array($_tmp='?ukey=manage_news')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='blog_post_list')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>	
	&raquo;
	<?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

</h1>

<form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="POST" name='MainForm'>
<input type="hidden" name="fACTION" value="ADD_NEWS" />

<table cellspacing="6">
<tr>
	<td><?php echo ((is_array($_tmp='str_priority')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
	<td>
		<input type=text name='DATA[priority]' value="<?php if ($this->_tpl_vars['NewsInfo']['priority']):  echo $this->_tpl_vars['NewsInfo']['priority'];  else: ?>0<?php endif; ?>" size="12" />
	</td>
</tr>
<tr>
	<td><?php echo ((is_array($_tmp='blog_postdate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
	</td>
	<td>
		<input type=text name='DATA[add_date]' value="<?php if ($this->_tpl_vars['NewsInfo']['add_date']):  echo $this->_tpl_vars['NewsInfo']['add_date'];  else:  echo $this->_tpl_vars['current_date'];  endif; ?>" size="12" />
	</td>
</tr>

<tr>
	<td><?php echo ((is_array($_tmp='blog_post_title')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <span class="notice">(<?php echo ((is_array($_tmp='blog_str_not_html')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
)</span>:</td>
	<td>
		<input type=text name='DATA[title]' value='<?php echo $this->_tpl_vars['NewsInfo']['title']; ?>
' size="50" />
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php echo ((is_array($_tmp='blog_post_body')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br />
		<textarea cols=40 rows=6 class="mceEditor" name="DATA[textToPublication]"><?php echo $this->_tpl_vars['NewsInfo']['textToPublication']; ?>
</textarea>
	</td>
</tr>
<tr>
	<td colspan="2">
		<?php echo ((is_array($_tmp='blog_post_newsletter_body')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:<br /> 
		<textarea cols=40 rows=6 class="mceEditor" name='DATA[textToMail]' style="width:100%;"><?php echo $this->_tpl_vars['NewsInfo']['textToMail']; ?>
</textarea>
	</td>
</tr>
<tr>
	<td align="right" valign="top"><?php echo ((is_array($_tmp='blog_emailposttosubscribers')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
	<td>
		<input type="checkbox" name="DATA[emailed]" value="1"<?php if ($this->_tpl_vars['NewsInfo']['emailed']): ?> checked="checked"<?php endif; ?> />
	</td>
</tr>
<tr>
	<td colspan="2">
		<input type=submit value="<?php echo ((is_array($_tmp='btn_add')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
	</td>
</tr>
</table>
</form>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/tiny_mce_config.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>