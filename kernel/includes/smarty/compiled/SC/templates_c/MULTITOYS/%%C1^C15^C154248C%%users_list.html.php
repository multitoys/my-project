<?php /* Smarty version 2.6.9, created on 2015-09-22 09:28:27
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 1, false),array('modifier', 'set_query', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 4, false),array('modifier', 'escape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 13, false),array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 79, false),array('modifier', 'transcape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 98, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 81, false),array('function', 'busers_city', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 87, false),array('function', 'busers_sform', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/users_list.html', 89, false),)), $this); ?>
<h1><?php echo ((is_array($_tmp='pgn_customers')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
<?php echo $this->_tpl_vars['MessageBlock']; ?>

<?php if ($this->_tpl_vars['page_enabled']): ?>
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
			<td><?php echo ((is_array($_tmp='usr_custinfo_first_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><input type="text" name="first_name" value='<?php echo ((is_array($_tmp=$_GET['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
			<td><?php echo ((is_array($_tmp='str_group')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td>
				<select name="custgroupID">
					<option value='0'><?php echo ((is_array($_tmp='str_any')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				<?php $_from = $this->_tpl_vars['customer_groups']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f_CGroup']):
?>
					<option value='<?php echo $this->_tpl_vars['f_CGroup']['custgroupID']; ?>
'<?php if ($_GET['custgroupID'] == $this->_tpl_vars['f_CGroup']['custgroupID']): ?> selected<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['f_CGroup']['custgroup_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
				</select>
			</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<td><?php echo ((is_array($_tmp='usr_custinfo_email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><input type="text" name="email" value='<?php echo ((is_array($_tmp=$_GET['email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
			<td><?php echo ((is_array($_tmp='usr_custinfo_last_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td><input type="text" name="last_name" value='<?php echo ((is_array($_tmp=$_GET['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'></td>
			<td><?php echo ((is_array($_tmp='str_status')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</td>
			<td>
				<select name="ActState">
					<option value='-1'<?php if ($_GET['ActState'] == -1): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='str_any')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<option value='1'<?php if ($_GET['ActState'] == 1): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='usr_account_activated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
					<option value='0'<?php if ($_GET['ActState'] == 0 && $_GET['ActState'] != ''): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='usr_account_notactivated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
				</select>
			</td>
			<td><input type="submit" name="search" value="<?php echo ((is_array($_tmp='btn_find')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"></td>
		</tr>
<!--		<?php if ($this->_tpl_vars['customers_has_been_exported_succefully'] != 1): ?>
		<tr>
		<td colspan="7"><p><?php echo ((is_array($_tmp='usr_export_userlist_to_csv')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p></td>
		</tr>
		<tr>
			<td><?php echo ((is_array($_tmp='prdimport_file_charset')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
			<td colspan="5">
			<select name="charset">
				<?php $_from = $this->_tpl_vars['charsets']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_charset']):
?>
				<option value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" <?php if ($this->_tpl_vars['default_charset'] == $this->_tpl_vars['_charset']): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['_charset'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
				<?php endforeach; endif; unset($_from); ?>
			</select>
			</td>
			<td>
			<input type="hidden" name="count_to_export" value="<?php echo $this->_tpl_vars['count_to_export']; ?>
">
			<input type="submit" name="export_to_excel" value="<?php echo ((is_array($_tmp='btn_export')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
">
			</td>
			</tr>
		<?php endif; ?>
-->
	</table>
	
	<br>
	
	</form>
	<p><?php echo $this->_tpl_vars['search_result_string']; ?>
</p>

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
		<td align="center"><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
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
	<?php $this->assign('customer_url', ((is_array($_tmp="did=&ukey=user_info&userID=".($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['customerID'])."&rdid=".($this->_tpl_vars['CurrentDivision']['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
	<?php $this->assign('custforadid', ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['customerID'])); ?>
	<tr class="<?php echo smarty_function_cycle(array('values' => "gridline1,gridline"), $this);?>
">
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['Login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php if (@CONF_BACKEND_SAFEMODE == 0):  echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['Email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  else:  echo ((is_array($_tmp='msg_safemode_info_blocked')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?></a></td>
<!-- 		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php $this->assign('custgroupID', $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['custgroupID']);  echo ((is_array($_tmp=$this->_tpl_vars['customer_groups'][$this->_tpl_vars['custgroupID']]['custgroup_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&nbsp;</a></td> -->
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo smarty_function_busers_city(array(), $this);?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['reg_datetime'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a></td>
		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'><?php echo smarty_function_busers_sform(array(), $this);?>
</a></td>
<!-- 		<td><a href='<?php echo $this->_tpl_vars['customer_url']; ?>
'>
		<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['ActivationCode']): ?>
			<?php echo ((is_array($_tmp='usr_account_notactivated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		<?php else: ?>
			<?php echo ((is_array($_tmp='usr_account_activated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

		<?php endif; ?>
		</a>
		</td> -->
		<td style="vertical-align:middle!important;" align="center"><a href='<?php echo ((is_array($_tmp="deleteCustomerID=".($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['customerID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' class="confirm_action" title="<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
"><img src="images/remove.gif" border="0" alt="<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" /></a></td>
	</tr>
<?php endfor; endif; ?>
	<tr class="gridsfooter">
		<td colspan="8"><?php echo $this->_tpl_vars['TotalFound']; ?>
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

<?php endif;  endif; ?>