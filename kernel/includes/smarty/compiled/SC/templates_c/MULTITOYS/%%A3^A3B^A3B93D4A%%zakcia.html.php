<?php /* Smarty version 2.6.9, created on 2015-09-27 18:24:25
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia.html', 26, false),array('modifier', 'date_format', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia.html', 30, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia.html', 34, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia.html', 27, false),)), $this); ?>
<style type="text/css">
<?php echo '

.akcia-body {
	padding: 10px;
}

'; ?>

</style>

<div class="akcia-body">
	<h3>Акции</h3>

	<?php if ($this->_tpl_vars['GridRows']): ?>
	<table class="grid" width="100%">
	<tr class="gridsheader">
		<td>ID</td>
		<td>Наименование</td>
		<td>Дата начала</td>
		<td>Дата окончания</td>
		<td align="center">Опубликован</td>
		<td align="center">Удалить</td>
	</tr>

	<?php $_from = $this->_tpl_vars['GridRows']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_row']):
?>
	<?php $this->assign('_answer_url', ((is_array($_tmp="&akciaID=".($this->_tpl_vars['_row']['akciaID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
	<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline,gridline1'), $this);?>
">
		<td nowrap="nowrap"><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo $this->_tpl_vars['_row']['akciaID']; ?>
</a></td>
		<td nowrap="nowrap"><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo $this->_tpl_vars['_row']['name_ru']; ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['start_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['_answer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['_row']['end_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
</a></td>
		<td align="center">
		<?php if ($this->_tpl_vars['_row']['enabled'] == 1): ?>
			<a href='<?php echo ((is_array($_tmp="unpublish=".($this->_tpl_vars['_row']['akciaID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title="<?php echo ((is_array($_tmp="Скрыть?")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" class="confirm_action">
				<img src="images_common/star1.gif" alt="<?php echo ((is_array($_tmp="Скрыть?")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			</a>
		<?php else: ?>
			<a href='<?php echo ((is_array($_tmp="publish=".($this->_tpl_vars['_row']['akciaID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title="<?php echo ((is_array($_tmp="Опубликовать?")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" class="confirm_action">
				<img src="images_common/starvote.gif" alt="<?php echo ((is_array($_tmp="Опубликовать?")) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			</a>
		<?php endif; ?>
		</td>
		<td align="center">
			<a href='<?php echo ((is_array($_tmp="delete=".($this->_tpl_vars['_row']['akciaID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' class="confirm_action">
				<img src="images_common/remove.gif" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
			</a>
		</td>
	</tr>
	<?php endforeach; endif; unset($_from); ?>

	<tr class="gridsfooter"> 
		
	</tr>
	</table>
	<?php endif; ?>
	<br>
	<br>
	<form method="POST" name="add_akcia">
		<input name="akcia_action" value="add" hidden />
		<input type="submit" value="Создать новую акцию" />
	</form>

</div>