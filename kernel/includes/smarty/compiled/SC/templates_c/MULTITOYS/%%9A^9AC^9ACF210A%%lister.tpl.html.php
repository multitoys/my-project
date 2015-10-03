<?php /* Smarty version 2.6.9, created on 2015-09-22 08:33:07
         compiled from backend/lister.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'backend/lister.tpl.html', 3, false),array('modifier', 'translate', 'backend/lister.tpl.html', 3, false),array('modifier', 'escape', 'backend/lister.tpl.html', 16, false),array('modifier', 'transcape', 'backend/lister.tpl.html', 20, false),)), $this); ?>
<?php if ($this->_tpl_vars['Lister']['LastPage'] > 1): ?>
	<?php if ($this->_tpl_vars['Lister']['CurrentPage'] > 1): ?>
		&nbsp; <a class="listerhref" href ="<?php echo ((is_array($_tmp="page=".($this->_tpl_vars['Lister']['CurrentPage']-1)."&show_all=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
">&lt;&lt; <?php echo ((is_array($_tmp='str_previous')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
	<?php endif; ?>
	<?php $_from = $this->_tpl_vars['Lister']['Range']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_page']):
?>
		&nbsp; <?php if ($this->_tpl_vars['_page'] != $this->_tpl_vars['Lister']['CurrentPage'] || $_GET['show_all']): ?><a class="listerhref" href="<?php echo ((is_array($_tmp="page=".($this->_tpl_vars['_page'])."&show_all=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo $this->_tpl_vars['_page']; ?>
</a> <?php else: ?><span style="color:navy"><?php echo $this->_tpl_vars['_page']; ?>
</span><?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	<?php if ($this->_tpl_vars['Lister']['CurrentPage'] < $this->_tpl_vars['Lister']['LastPage']): ?>
		&nbsp; <a class="listerhref" href ="<?php echo ((is_array($_tmp="page=".($this->_tpl_vars['Lister']['CurrentPage']+1)."&show_all=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='str_next')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 &gt;&gt;</a>
	<?php endif; ?>

	<?php if ($this->_tpl_vars['rows']): ?>&nbsp; |&nbsp; <form method="post" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" style="display: inline;">
	<?php echo ((is_array($_tmp='str_show_rows')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: 
	<select name="rows_num">
	<?php $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_title'] => $this->_tpl_vars['_value']):
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['rows_num'] == $this->_tpl_vars['_value']): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
	<?php endforeach; endif; unset($_from); ?>
			<option value="show_all" <?php if ($this->_tpl_vars['rows_show_all']): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='str_all')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
	</select>
	<input type="submit" value="<?php echo ((is_array($_tmp='btn_apply')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" />
</form><?php endif;  else:  if ($this->_tpl_vars['rows']): ?>
<form method="post" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" style="display: inline;">
	<?php echo ((is_array($_tmp='str_show_rows')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: 
	<select name="rows_num">
	<?php $_from = $this->_tpl_vars['rows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_title'] => $this->_tpl_vars['_value']):
?>
			<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['rows_num'] == $this->_tpl_vars['_value']): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
	<?php endforeach; endif; unset($_from); ?>
			<option value="show_all" <?php if ($this->_tpl_vars['rows_show_all']): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp='str_all')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
	</select>
	<input type="submit" value="<?php echo ((is_array($_tmp='btn_apply')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" />
</form>
<?php endif;  endif; ?>