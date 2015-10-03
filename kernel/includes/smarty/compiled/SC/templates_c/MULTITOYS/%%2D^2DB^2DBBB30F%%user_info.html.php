<?php /* Smarty version 2.6.9, created on 2015-09-22 11:31:45
         compiled from backend/user_info.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'backend/user_info.html', 4, false),array('modifier', 'translate', 'backend/user_info.html', 4, false),array('modifier', 'replace', 'backend/user_info.html', 6, false),array('modifier', 'escape', 'backend/user_info.html', 6, false),)), $this); ?>
<table id="tbl-block" cellspacing="0" cellpadding="0">
<tr height="1%">
	<td colspan="2"  height="1%">
		<h1 class="breadcrumbs"><a href='<?php if ($_GET['rdid']):  echo ((is_array($_tmp="userID=&rdid=&did=".($_GET['rdid'])."&ukey=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  else:  echo ((is_array($_tmp='?ukey=customers')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  endif; ?>'><?php echo ((is_array($_tmp='pgn_customers')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
		&raquo;
		<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp='pgn_user_info')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '%CUSTOMER_NAME%', $this->_tpl_vars['customer_name']) : smarty_modifier_replace($_tmp, '%CUSTOMER_NAME%', $this->_tpl_vars['customer_name'])))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

		</h1>
		<?php echo $this->_tpl_vars['MessageBlock']; ?>

	</td>
</tr>
<?php if ($this->_tpl_vars['user_info_not_found']): ?>
<tr>
	<td id="left-block">&nbsp;</td><td id="right-block">&nbsp;</td>
</tr>
<?php else: ?>
<tr>
<td id="left-block">
	<div id="left-div">
	<ul>
	<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['UserAccountDivs']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['show'] = true;
$this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = $this->_sections['j']['loop'];
    if ($this->_sections['j']['total'] == 0)
        $this->_sections['j']['show'] = false;
} else
    $this->_sections['j']['total'] = 0;
if ($this->_sections['j']['show']):

            for ($this->_sections['j']['index'] = $this->_sections['j']['start'], $this->_sections['j']['iteration'] = 1;
                 $this->_sections['j']['iteration'] <= $this->_sections['j']['total'];
                 $this->_sections['j']['index'] += $this->_sections['j']['step'], $this->_sections['j']['iteration']++):
$this->_sections['j']['rownum'] = $this->_sections['j']['iteration'];
$this->_sections['j']['index_prev'] = $this->_sections['j']['index'] - $this->_sections['j']['step'];
$this->_sections['j']['index_next'] = $this->_sections['j']['index'] + $this->_sections['j']['step'];
$this->_sections['j']['first']      = ($this->_sections['j']['iteration'] == 1);
$this->_sections['j']['last']       = ($this->_sections['j']['iteration'] == $this->_sections['j']['total']);
?>
	 	<li><div <?php if ($this->_tpl_vars['CurrentDivision']['id'] == $this->_tpl_vars['UserAccountDivs'][$this->_sections['j']['index']]['id']): ?>class="current"<?php endif; ?>>
	 		<a href='<?php echo ((is_array($_tmp="&ukey=&did=".($this->_tpl_vars['UserAccountDivs'][$this->_sections['j']['index']]['id']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp=$this->_tpl_vars['UserAccountDivs'][$this->_sections['j']['index']]['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  if ($this->_tpl_vars['UserAccountDivs'][$this->_sections['j']['index']]['ukey'] == 'admin_user_order_history'): ?>&nbsp;(<?php echo $this->_tpl_vars['customer_orders_count']; ?>
)<?php endif; ?></a>
	 	</div></li>
	 <?php endfor; endif; ?>

	</ul>
	
	</div>
	<script type="text/javascript" src="<?php echo @URL_JS; ?>
/niftycube.js"></script>
	<script type="text/javascript">
	Nifty('#left-div', 'tl bl');
	Nifty('div.current', 'tl bl');
	</script>
</td>


<td id="right-block">
	<?php if ($this->_tpl_vars['UserInfoFile']): ?>
    <table border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td><h2><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2></td>
            <td valign="top" style="padding-left: 20px;"><?php if ($this->_tpl_vars['eLink']): ?>&nbsp;<a href="<?php echo $this->_tpl_vars['eLink']['href']; ?>
" onClick="<?php echo $this->_tpl_vars['eLink']['onclick']; ?>
" id="elink" class="dashed_link"><?php echo $this->_tpl_vars['eLink']['title']; ?>
</a><?php endif; ?></td>
	   </tr>
    </table>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['UserInfoFile'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
</td>
</tr>
<?php endif; ?>
</table>