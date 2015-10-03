<?php /* Smarty version 2.6.9, created on 2015-10-02 13:45:51
         compiled from backend/catalog_discuss.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'backend/catalog_discuss.tpl.html', 4, false),array('modifier', 'translate', 'backend/catalog_discuss.tpl.html', 4, false),array('modifier', 'escape', 'backend/catalog_discuss.tpl.html', 6, false),array('modifier', 'linewrap', 'backend/catalog_discuss.tpl.html', 8, false),array('modifier', 'truncate', 'backend/catalog_discuss.tpl.html', 53, false),array('function', 'cycle', 'backend/catalog_discuss.tpl.html', 51, false),)), $this); ?>

<?php if ($this->_tpl_vars['answer']): ?>
<h1 class="breadcrumbs"><a href='<?php echo ((is_array($_tmp="answer=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp='pgn_product_reviews')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
&raquo;
<?php echo ((is_array($_tmp='prdreview_reply')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <?php echo ((is_array($_tmp=$this->_tpl_vars['discussion']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</h1>

<h2 class="post_title"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['discussion']['Topic'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('linewrap', true, $_tmp) : smarty_modifier_linewrap($_tmp)); ?>
 / <?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['discussion']['Author'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('linewrap', true, $_tmp) : smarty_modifier_linewrap($_tmp)); ?>
</h2>
<div class="post_date"><?php echo ((is_array($_tmp=$this->_tpl_vars['discussion']['add_time'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</div>
<div class="post_content"><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['discussion']['Body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('linewrap', true, $_tmp) : smarty_modifier_linewrap($_tmp)); ?>
</div>

<h2>Reply</h2>

<form action='<?php echo ((is_array($_tmp="")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' method="post" class="marginblock">

<p>
	<?php echo ((is_array($_tmp='str_your_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
	<br />
	<input type="text" name='newAuthor' style="width: 250px;" />
</p>
<p>
	<?php echo ((is_array($_tmp='str_subject')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
	<br />
	<input type="text" name='newTopic' value='Re: <?php echo ((is_array($_tmp=$this->_tpl_vars['discussion']['Topic'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' style="width: 250px;" />
</p>
<p>
	<?php echo ((is_array($_tmp='prddiscussion_body')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:
	<br />
	<textarea name='newBody' rows="7"  style="width: 400px;"></textarea>
</p>

<p>
	<input type=submit value='OK' name='add'>
	<input type=reset value='<?php echo ((is_array($_tmp='btn_clear')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
'>
</p>

</form>	

<?php else: ?>

<h1><?php echo ((is_array($_tmp='pgn_product_reviews')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  if ($this->_tpl_vars['rss_link']): ?>&nbsp;<a href="<?php echo @URL_ROOT; ?>
/<?php echo $this->_tpl_vars['rss_link']; ?>
"><img src="<?php echo @URL_IMAGES_COMMON; ?>
/rss-feed.png" alt="RSS 2.0" style="padding-left:10px;"></a><?php endif; ?></h1>

<?php if ($this->_tpl_vars['GridRows']): ?>
<table class="grid" width="100%">
<tr class="gridsheader">
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/gridheader.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</tr>

<?php $_from = $this->_tpl_vars['GridRows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
 $this->assign('_answer_url', ((is_array($_tmp="&answer=".($this->_tpl_vars['_row']['DID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
	<td nowrap="nowrap"><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo $this->_tpl_vars['_row']['add_time']; ?>
</a></td>
	<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_row']['product_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)); ?>
</a></td>
	<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_row']['Author'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 20) : smarty_modifier_truncate($_tmp, 20)); ?>
</a></td>
	<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_row']['Topic'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 30, '...', true) : smarty_modifier_truncate($_tmp, 30, '...', true)); ?>
</a></td>
	<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_row']['Body'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('truncate', true, $_tmp, 50, '...', true) : smarty_modifier_truncate($_tmp, 50, '...', true)); ?>
</a></td>
	<td align="center">
		<a href='<?php echo ((is_array($_tmp="delete=".($this->_tpl_vars['_row']['DID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' class="confirm_action">
			<img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
		</a>
	</td>
</tr>
<?php endforeach; endif; unset($_from); ?>

<tr class="gridsfooter"> 
	<td colspan="<?php echo $this->_tpl_vars['GridHeadersNum']; ?>
"><?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/lister.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
</tr>
</table>
<?php else: ?>
	<?php echo ((is_array($_tmp='str_empty_list')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

<?php endif;  endif; ?>