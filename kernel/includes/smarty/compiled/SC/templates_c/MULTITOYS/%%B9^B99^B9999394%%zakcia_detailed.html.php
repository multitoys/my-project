<?php /* Smarty version 2.6.9, created on 2015-09-27 18:24:31
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia_detailed.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'date_format', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zakcia_detailed.html', 27, false),)), $this); ?>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/jquery.1.4.2.js"></script>

<style type="text/css">
<?php echo '

.akcia-body {
	padding: 10px;
}

'; ?>

</style>

<div class="akcia-body">
	<h3>Новая акция</h3>
	<br>
	<br>
	<form name="add_akcia" method="POST">
	<input name="akcia_action" value="<?php if ($this->_tpl_vars['AkciaData']): ?>update_akcia<?php else: ?>save_akcia<?php endif; ?>" hidden />
	<?php if ($this->_tpl_vars['AkciaData']): ?><input name="akciaID" value="<?php echo $this->_tpl_vars['AkciaData'][0]['akciaID']; ?>
" hidden /> <?php endif; ?>
	<table>
		<tr>
			<td>Название:</td>
			<td><input type="text" name="akcia_name" <?php if ($this->_tpl_vars['AkciaData']): ?> value="<?php echo $this->_tpl_vars['AkciaData'][0]['name_ru']; ?>
" <?php endif; ?> /></td>
		</tr>
		<tr>
			<td>Дата начала:</td>
			<td><input type="text" class="datepickerTimeField" name="akcia_start" <?php if ($this->_tpl_vars['AkciaData']): ?> value='<?php echo ((is_array($_tmp=$this->_tpl_vars['AkciaData'][0]['start_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
' <?php endif; ?>/></td>
		</tr>
		<tr>
			<td>Дата окончания:</td>
			<td><input type="text" class="datepickerTimeField" name="akcia_end" <?php if ($this->_tpl_vars['AkciaData']): ?> value='<?php echo ((is_array($_tmp=$this->_tpl_vars['AkciaData'][0]['end_date'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
' <?php endif; ?>/></td>
		</tr>
		<tr>
			<td>Описание:</td>
			<td><textarea id="zaBody" class="mceEditor" name="za_body" cols="60" rows="10" ><?php if ($this->_tpl_vars['AkciaData']): ?> <?php echo $this->_tpl_vars['AkciaData'][0]['text_ru']; ?>
 <?php endif; ?></textarea></td>
		</tr>
		<tr>
			<td>Опубликовать:</td>
			<td><input type="checkbox" name="akcia_enabled" <?php if ($this->_tpl_vars['AkciaData'] && $this->_tpl_vars['AkciaData'][0]['enabled'] == 1): ?> checked <?php endif; ?> /></td>
		</tr>
		<tr>
			<td><input type="submit" value="Сохранить" /></td>
			<td></td>
		</tr>
	</table>
</form>
</div>

<link type="text/css" href="<?php echo @URL_CSS; ?>
/latest.css" rel="Stylesheet" />
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/latest.js"></script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/tiny_mce_config.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>