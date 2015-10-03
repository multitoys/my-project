<?php /* Smarty version 2.6.9, created on 2015-09-22 17:52:52
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 17, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 22, false),array('modifier', 'escape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 26, false),array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 59, false),array('modifier', 'date_format', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 61, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/zauth_log.html', 60, false),)), $this); ?>
<?php echo '
<style>

.za_body {
	padding: 10px;
}

.za_list td{

}

</style>
'; ?>


<div class="za_body">
	<h3>Журнал сессий</h3>
<form name="MainForm" method="get" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
">
	<input type="hidden" name="did" value="<?php echo $this->_tpl_vars['CurrentDivision']['id']; ?>
" />
	
	<table border="0" cellspacing="1" cellpadding="5">
		<tr>
			<td colspan="7"><p><?php echo ((is_array($_tmp='usr_customer_search_descr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p></td>
		</tr>
		<tr>
			<td><?php echo ((is_array($_tmp='usr_custinfo_login')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><input type="text" name="login" value='<?php echo ((is_array($_tmp=$_GET['login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
				<td><?php echo ((is_array($_tmp='usr_custinfo_last_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
				<td><input type="text" name="last_name" value='<?php echo ((is_array($_tmp=$_GET['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
				<td><?php echo ((is_array($_tmp='usr_custinfo_first_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
				<td><input type="text" name="first_name" value='<?php echo ((is_array($_tmp=$_GET['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
			<td>IP-адрес</td>
			<td><input type="text" name="IP_address" value='<?php echo ((is_array($_tmp=$_GET['IP_address'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
			<td>Тип события:</td>
						<td>
				<select name="type_event">
					<option value='-1'<?php if ($_GET['type_event'] == -1): ?> selected="selected"<?php endif; ?>>Все события</option>
					<option value='1'<?php if ($_GET['type_event'] == 1): ?> selected="selected"<?php endif; ?>>Вход</option>
					<option value='2'<?php if ($_GET['type_event'] == 2): ?> selected="selected"<?php endif; ?>>Попытка</option>
					<option value='3'<?php if ($_GET['type_event'] == 3): ?> selected="selected"<?php endif; ?>>Выход</option>
					<option value='4'<?php if ($_GET['type_event'] == 4): ?> selected="selected"<?php endif; ?>>WARNING</option>
				</select>
			<td><input type="submit" name="search" value="<?php echo ((is_array($_tmp='btn_find')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"></td>
		</tr>
	</table>
	
	<br>
	
	</form>
<?php if ($this->_tpl_vars['GridRows']): ?>

<?php if ($this->_tpl_vars['navigator']): ?><p><?php echo $this->_tpl_vars['navigator'];  endif; ?>
<p>
<table width="100%" border="0" cellpadding="0" cellspacing="0" class="grid">
	<tr class="gridsheader">
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/gridheader.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</tr>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['GridRows']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
?>
	<?php $this->assign('customer_url', ((is_array($_tmp="did=&ukey=user_info&userID=".($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['customerID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
	<tr class="<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
">
		<td><!-- <a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'> --><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['date_event'])) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>
<!-- </a> --></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><font color="blue"><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</font></a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['Login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><!-- <a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'> --><font color="blue"><strong><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['IP_address'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</strong></font><!-- </a> --></td>
		<td><!-- <a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'> --><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['region'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<!-- </a> --></td>
		<td><!-- <a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'> --><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['city'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<!-- </a> --></td>
		<td><!-- <a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'> -->
			<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['type_event'] == 1): ?><font color="green">Вход</font><?php endif; ?>
			<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['type_event'] == 3): ?><font color="blue">Выход</font><?php endif; ?>
			<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['type_event'] == 2): ?><font color="orange">Попытка</font><?php endif; ?>
			<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['type_event'] == 4): ?><font color="red">WARNING</font><?php endif; ?>
		<!-- </a> --></td>
		<td colspan="2" ><span style="font-size:85%;color:navy"><!--<a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'>--><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['user_agent'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
<!-- </a> --></span></td>
				<!-- <td><span style="font-size:85%;color:grey;background-color: rgba(230, 230, 250, 0.5);">
				</span></td> -->
			</tr>
<?php endfor; endif; ?>
	<tr class="gridsfooter">
		<td colspan="11"><?php echo $this->_tpl_vars['TotalFound']; ?>
 &nbsp;<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/lister.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
	</tr>
</table>
<?php else: ?>
<p>&lt;<?php echo ((is_array($_tmp='lbl_not_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
&gt;</p>
<?php endif; ?>
</div>