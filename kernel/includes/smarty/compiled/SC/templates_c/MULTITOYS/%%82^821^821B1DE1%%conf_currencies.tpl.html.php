<?php /* Smarty version 2.6.9, created on 2015-10-02 13:55:48
         compiled from backend/conf_currencies.tpl.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/conf_currencies.tpl.html', 5, false),array('modifier', 'escape', 'backend/conf_currencies.tpl.html', 5, false),array('modifier', 'set_query_html', 'backend/conf_currencies.tpl.html', 13, false),)), $this); ?>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/JsHttpRequest.js"></script>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/conf_currencies.js"></script>
<script type="text/javascript">
var translate = {
	'loc_del_confirmation': '<?php echo ((is_array($_tmp=((is_array($_tmp='loc_del_confirmation')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
'
	};
</script>

<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>

<?php echo $this->_tpl_vars['MessageBlock']; ?>


<div style="float:left;clear:both;"><h2><?php echo ((is_array($_tmp='curr_default_currency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 (<a href="<?php echo ((is_array($_tmp='?ukey=change_default_currency')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='curr_change_default_currency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>)</h2></div>
<?php $this->assign('_currency', $this->_tpl_vars['default_currency']); ?>
<div class="currency_block" cid="<?php echo $this->_tpl_vars['_currency']['CID']; ?>
">

	<strong><span class="ff_Name"><?php echo $this->_tpl_vars['_currency']['Name']; ?>
</span> (<span class="ff_currency_iso_3"><?php echo $this->_tpl_vars['_currency']['currency_iso_3']; ?>
</span>)</strong>
	<br />
	<?php echo ((is_array($_tmp='curr_curr_output_example')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong class="ff_output_example"><?php echo $this->_tpl_vars['_currency']['output_example']; ?>
</strong>
	<?php if ($this->_tpl_vars['_currency']['CID'] != $this->_tpl_vars['default_currency']['CID']): ?>
	<br />
	<?php echo ((is_array($_tmp='curr_exchange_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong>1 <?php echo $this->_tpl_vars['default_currency']['currency_iso_3']; ?>
 = <input class="exchange_rate" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_currency']['currency_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="4" type="text" style="text-align: right;" /> <span class="ff_currency_iso_3"><?php echo $this->_tpl_vars['_currency']['currency_iso_3']; ?>
</span> <img src="./images_common/processing.gif" class="save_exchange_processing_image" style="display:none;" /><input class="save_exchange_rate_handler" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="button" /></strong>
	<?php endif; ?>
	<br />
	
	<a href="#edit" class="edit_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>

	<?php if ($this->_tpl_vars['_currency']['CID'] != $this->_tpl_vars['default_currency']['CID']): ?>
	&nbsp;
	
	<a href="#delete" class="delete_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
	<?php endif; ?>
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/currency_edit_form.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>

<div style="float:left;clear:both;"><h2><?php echo ((is_array($_tmp='curr_other_currencies')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h2></div>

<?php $_from = $this->_tpl_vars['currencies']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_currency']):
 if ($this->_tpl_vars['_currency']['CID'] != $this->_tpl_vars['default_currency']['CID']): ?>

<div class="currency_block" cid="<?php echo $this->_tpl_vars['_currency']['CID']; ?>
">

	<strong><span class="ff_Name"><?php echo $this->_tpl_vars['_currency']['Name']; ?>
</span> (<span class="ff_currency_iso_3"><?php echo $this->_tpl_vars['_currency']['currency_iso_3']; ?>
</span>)</strong>
	<br />
	<?php echo ((is_array($_tmp='curr_curr_output_example')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong class="ff_output_example"><?php echo $this->_tpl_vars['_currency']['output_example']; ?>
</strong>
	<?php if ($this->_tpl_vars['_currency']['CID'] != $this->_tpl_vars['default_currency']['CID']): ?>
	<br />
	<?php echo ((is_array($_tmp='curr_exchange_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong>1 <?php echo $this->_tpl_vars['default_currency']['currency_iso_3']; ?>
 = <input class="exchange_rate" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['_currency']['currency_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="4" type="text" style="text-align: right;" /> <span class="ff_currency_iso_3"><?php echo $this->_tpl_vars['_currency']['currency_iso_3']; ?>
</span> <img src="./images_common/processing.gif" class="save_exchange_processing_image" style="display:none;" /><input class="save_exchange_rate_handler" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="button" /></strong>
	<?php endif; ?>
	<br />
	
	<a href="#edit" class="edit_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>

	<?php if ($this->_tpl_vars['_currency']['CID'] != $this->_tpl_vars['default_currency']['CID']): ?>
	&nbsp;
	
	<a href="#delete" class="delete_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
	<?php endif; ?>
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/currency_edit_form.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

</div>
<?php endif;  endforeach; endif; unset($_from); ?>
<div id="new-currencies-container"></div>

<div style="display:none;" id="new-currency" class="currency_block" cid="0">

	<strong><span class="ff_Name"></span> (<span class="ff_currency_iso_3"></span>)</strong>
	<br />
	<?php echo ((is_array($_tmp='curr_curr_output_example')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong class="ff_output_example"></strong>
	<br />
	<?php echo ((is_array($_tmp='curr_exchange_rate')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <strong>1 <?php echo $this->_tpl_vars['default_currency']['currency_iso_3']; ?>
 = <input class="exchange_rate" value="1" size="4" type="text" style="text-align: right;" /> <span class="ff_currency_iso_3"></span>
	<img src="./images_common/processing.gif" class="save_exchange_processing_image" style="display:none;" /><input class="save_exchange_rate_handler" value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" type="button" /></strong>
	<br />
	
	<a href="#edit" class="edit_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>

	&nbsp;
	
	<a href="#delete" class="delete_currency_handler"><?php echo ((is_array($_tmp=((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</a>
	
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/currency_edit_form.html", 'smarty_include_vars' => array('_currency' => '')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>

<div class="currency_block" cid="0">
<h2><a href="#add_currency" class="edit_currency_handler"><?php echo ((is_array($_tmp='curr_add_currency')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></h2>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/currency_edit_form.html", 'smarty_include_vars' => array('_currency' => $this->_tpl_vars['currency_default_values'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</div>