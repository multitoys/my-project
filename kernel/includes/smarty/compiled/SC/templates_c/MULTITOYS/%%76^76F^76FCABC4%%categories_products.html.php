<?php /* Smarty version 2.6.9, created on 2015-09-22 17:30:42
         compiled from backend/categories_products.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/categories_products.html', 5, false),array('modifier', 'cat', 'backend/categories_products.html', 14, false),array('modifier', 'transcape', 'backend/categories_products.html', 23, false),array('modifier', 'escape', 'backend/categories_products.html', 23, false),array('modifier', 'default', 'backend/categories_products.html', 23, false),array('modifier', 'set_query', 'backend/categories_products.html', 35, false),array('modifier', 'set_query_html', 'backend/categories_products.html', 43, false),array('function', 'cycle', 'backend/categories_products.html', 126, false),)), $this); ?>
<table id="tbl-block" cellspacing="0" cellpadding="0">
<tr height="1%">
	<td colspan="2"  height="1%">
		<h1><?php echo ((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['name'])) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</h1>
	
		<?php echo $this->_tpl_vars['MessageBlock']; ?>

	</td>
</tr>
<tr>
<td id="left-block">
	<div id="left-top">
	<form method="POST" name="search_form" action='<?php echo ((is_array($_tmp=$this->_tpl_vars['urlToSubmit'])) ? $this->_run_mod_handler('cat', true, $_tmp, "&search=yes") : smarty_modifier_cat($_tmp, "&search=yes")); ?>
'>
				<input type="text" class="input_message" rel="<?php echo ((is_array($_tmp='cpt_lbl_product_search')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" name="search_value" value="<?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['search_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, 'cpt_lbl_product_search') : smarty_modifier_default($_tmp, 'cpt_lbl_product_search')))) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" />
		<input type="submit" value="<?php echo ((is_array($_tmp='btn_find')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" />
		<?php if ($this->_tpl_vars['searched_done']): ?>
			
		<?php endif; ?>
		</form>
	</div>
	<?php if (true || ! $this->_tpl_vars['searched_done']): ?>
	<div id="left-div">
	<ul>		<li><div <?php if (1 == $this->_tpl_vars['categoryID'] && ! $this->_tpl_vars['searched_done']): ?>class="current"<?php endif; ?>>
			<img style="visibility:hidden;" src="images_common/minus.gif" alt="<?php echo ((is_array($_tmp='btn_collapse')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
" border="0" />
			<a href="<?php echo ((is_array($_tmp="&categoryID=1&search")) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
"><?php echo ((is_array($_tmp='prdcat_category_root')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
			(<?php echo $this->_tpl_vars['products_in_root_category']; ?>
)
		</div></li>
	 <?php $_from = $this->_tpl_vars['categories']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_category']):
?>
	 	<li><div <?php if ($this->_tpl_vars['_category']['categoryID'] == $this->_tpl_vars['categoryID'] && ! $this->_tpl_vars['searched_done']): ?>class="current"<?php endif; ?>>
			<?php unset($this->_sections['j']);
$this->_sections['j']['name'] = 'j';
$this->_sections['j']['loop'] = is_array($_loop=$this->_tpl_vars['_category']['level']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['j']['max'] = (int)$this->_tpl_vars['_category']['level'];
$this->_sections['j']['show'] = true;
if ($this->_sections['j']['max'] < 0)
    $this->_sections['j']['max'] = $this->_sections['j']['loop'];
$this->_sections['j']['step'] = 1;
$this->_sections['j']['start'] = $this->_sections['j']['step'] > 0 ? 0 : $this->_sections['j']['loop']-1;
if ($this->_sections['j']['show']) {
    $this->_sections['j']['total'] = min(ceil(($this->_sections['j']['step'] > 0 ? $this->_sections['j']['loop'] - $this->_sections['j']['start'] : $this->_sections['j']['start']+1)/abs($this->_sections['j']['step'])), $this->_sections['j']['max']);
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
?>&nbsp;&nbsp;&nbsp;<?php endfor; endif; ?>
			<?php if (! $this->_tpl_vars['_category']['ExpandedCategory']): ?>
				<?php if ($this->_tpl_vars['_category']['ExistSubCategories']): ?>
					<a href='<?php echo ((is_array($_tmp="&expandCat=".($this->_tpl_vars['_category']['categoryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><img src="images_common/plus.gif" alt="<?php echo ((is_array($_tmp='btn_expand')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" /></a>
				<?php else: ?>
					<img src="images_common/minus.gif" style="visibility:hidden;" />
				<?php endif; ?>
			<?php else: ?>
				<?php if ($this->_tpl_vars['_category']['ExistSubCategories']): ?>
					<a href='<?php echo ((is_array($_tmp="&shrinkCat=".($this->_tpl_vars['_category']['categoryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><img src="images_common/minus.gif" alt="<?php echo ((is_array($_tmp='btn_collapse')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" /></a>
				<?php else: ?>
					<img src="images_common/minus.gif" style="visibility:hidden;" />
				<?php endif; ?>
			<?php endif; ?>
			
			<a href='<?php echo ((is_array($_tmp="&categoryID=".($this->_tpl_vars['_category']['categoryID'])."&expandCat=".($this->_tpl_vars['_category']['categoryID'])."&search")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['_category']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('default', true, $_tmp, "(no name)") : smarty_modifier_default($_tmp, "(no name)")); ?>
</a>
			<?php if (! $this->_tpl_vars['_category']['ExpandedCategory']): ?>(<?php echo $this->_tpl_vars['_category']['products_count_admin']; ?>
)<?php else: ?>(<?php echo $this->_tpl_vars['_category']['products_count_category']; ?>
)<?php endif; ?>
		</div></li>
	 <?php endforeach; endif; unset($_from); ?>

	</ul>

	<div id="left-bottom">
	
	<p>	
	<input type="button" value='<?php echo ((is_array($_tmp='btn_addcategory')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
' class="goto" rel='<?php echo ((is_array($_tmp="?ukey=category_settings&parent=".($this->_tpl_vars['categoryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' />
	</p>
	
	</div>
	
	</div>
	<?php endif; ?>
	<script type="text/javascript" src="<?php echo @URL_JS; ?>
/niftycube.js"></script>
	<script type="text/javascript" src="<?php echo @URL_JS; ?>
/functions.js"></script>
	<script type="text/javascript" src="<?php echo @URL_JS; ?>
/widget_checkout.js"></script>	
	<script type="text/javascript">
	Nifty('#left-div', 'tl bl');
	Nifty('div.current', 'tl bl');
	</script>
</td>


<td id="right-block">
	<?php if ($this->_tpl_vars['searched_done']): ?>
	<h2><?php echo $this->_tpl_vars['searched_count']; ?>
</h2>
	<?php else: ?>
	<h2>
	<?php if ($this->_tpl_vars['categoryID'] != 1): ?>
		<?php echo ((is_array($_tmp=$this->_tpl_vars['category_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>

	<?php else: ?>
		<?php echo ((is_array($_tmp='prdcat_category_root')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

	<?php endif; ?>
	
	<?php if ($this->_tpl_vars['categoryID'] != 1): ?>
	&nbsp;
	<a href='<?php echo ((is_array($_tmp="ukey=category_settings&categoryID=".($this->_tpl_vars['categoryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
'><?php echo ((is_array($_tmp='prdcat_btn_edit_category')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</a>
	&nbsp;
	<a href='<?php echo ((is_array($_tmp="action=delete_category")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title="<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" class="confirm_action"><?php echo ((is_array($_tmp='prdcat_btn_delete_category')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
</a>
	<?php endif; ?>
	</h2>
	
	<p>
	<input value="<?php echo ((is_array($_tmp='btn_addproduct')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" type="button" class="goto" rel='<?php echo ((is_array($_tmp="?ukey=product_settings&categoryID=".($this->_tpl_vars['categoryID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' />
	</p>
	
	<?php if ($this->_tpl_vars['categoryID'] == 1): ?>
	<p style="text-align: center"><?php echo ((is_array($_tmp='prd_msg_root_warning')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	<?php endif; ?>
	
	<?php endif; ?>
	
	

	<?php if ($this->_tpl_vars['GridRows']): ?>
	<form action='<?php echo $this->_tpl_vars['urlToSubmit']; ?>
' method="post" name="MainForm" id="MainForm">
	<input name="action" value="save_products" type="hidden" />
		
		<table width="100%" border="0" cellpadding="0" cellspacing="0" class="grid">
		<tr class="gridsheader">
			<td><input type="checkbox" class="groupcheckbox" id="group-box" rel="select_product" /></td>
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
		<?php $this->assign('product_url', ((is_array($_tmp="?ukey=product_settings&productID=".($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID'])."&categoryID=&expandCat=&offset=&sort=&sort_dir=&search=&search_value=")) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
		<tr class="<?php echo smarty_function_cycle(array('values' => 'gridline1,gridline'), $this); if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['in_stock'] <= 0 && @CONF_CHECKSTOCK): ?> gridline_outofstock<?php endif; ?>">
		
			<td><input name="selected_product_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
" class="checkbox select_product" rel="group-box" type="checkbox" value="1" /></td>
			<td><a href="<?php echo $this->_tpl_vars['product_url']; ?>
"><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&nbsp;</a></td>
			<td width="50%"><a href="<?php echo $this->_tpl_vars['product_url']; ?>
"><?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['thumbnail'] != ''): ?><img src="<?php echo @URL_PRODUCTS_PICTURES; ?>
/<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['thumbnail']; ?>
" width="50"><?php elseif ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['picture'] != ''): ?><img src="<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['picture']; ?>
 width="50"><?php elseif ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['big_picture'] != ''): ?><img src="<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['big_picture']; ?>
 width="50"><?php endif;  echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
&nbsp;<?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['enabled'] != 1): ?><span class="notice"><?php echo ((is_array($_tmp='prep_invisible_in_storefront')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span><?php endif; ?></a></td>
			<td style="text-align:center;"><input name="enabled_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
" type="checkbox" <?php if (! $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['enabled']): ?>checked<?php endif; ?> /></td>
			<td style="text-align:center;"><input name="ordering_available_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
" type="checkbox" <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['ordering_available']): ?>checked<?php endif; ?> /></td>
			<td align="right"><a href="<?php echo $this->_tpl_vars['product_url']; ?>
"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['customers_rating']; ?>
&nbsp;</a></td>
			<td align="center" style="border-left: solid 1px #F5F0BB;border-right: solid 1px #F5F0BB;"><input type="text" class="multiply_price" name="price_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
" size="10" value="<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['Price']; ?>
" /></td>
			<?php if (@CONF_CHECKSTOCK == 1): ?>
			<td><input type="text" name="left_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
" size="5" value="<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['in_stock']; ?>
" /></td>
			<?php endif; ?>
			<td align="right"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['items_sold'];  if (! $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['ordering_available']): ?><div class="notice"><?php echo ((is_array($_tmp='prd_ordering_not_available')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div><?php endif; ?></td>
			<td align="center"><input type='text' name='sort_order_<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']; ?>
' value="<?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['sort_order']; ?>
" size="2" /></td>
			<td style="border-right: solid 1px #F5F0BB;"><a href='<?php echo ((is_array($_tmp="action=delete_product&productID=".($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['productID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
' title='<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
' class="confirm_action"><img src="images_common/remove.gif" border="0" alt='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
' /></a></td>
		</tr>	
		<?php endfor; endif; ?>
	
		<tr class="gridsfooter"> 
			<td colspan="4">
				<?php echo $this->_tpl_vars['navigatorHtml']; ?>

								
				<p>
				
					<div style="float: left;">
						<?php echo ((is_array($_tmp='prdcat_with_selected')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <input type="submit" name="delete_selected" class="confirm_action" title="<?php echo ((is_array($_tmp='cnfrm_delete')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" value='<?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
					</div>
					<div style="float: left;">
						&nbsp;
						<input name="categoryID" id="parent-category-categoryID" value="<?php echo ((is_array($_tmp=@$this->_tpl_vars['parent_category']['categoryID'])) ? $this->_run_mod_handler('default', true, $_tmp, 1) : smarty_modifier_default($_tmp, 1)); ?>
" type="hidden" />
					</div>
					<div style="float: left;">	
						&nbsp;					
						<input name="move_selected" type="submit" value='<?php echo ((is_array($_tmp='prdcat_category_move_to')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' id="choose-parentcategory-handler"/>
					</div>
					<div style="float: left;">	
						&nbsp;					
						<input name="duplicate_selected" type="submit" value='<?php echo ((is_array($_tmp='prdcat_products_duplicate_selected')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' id="duplicate_products-handler"/>
					</div>
			</td>
			<td  colspan="<?php if (@CONF_CHECKSTOCK == 1): ?>5<?php else: ?>4<?php endif; ?>" style="border-left: solid 1px #F5F0BB;border-right: solid 1px #F5F0BB;border-bottom: solid 1px #F5F0BB;">
			&uarr;<span style="font-size:smaller;"><?php echo ((is_array($_tmp='prd_multiply_label')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
			<input style="font-size:smaller;" type="text" id="multiply_price_value" value="1.000" size="5">
			<span id="multiply-price-handler" style="font-size:smaller;border-bottom:1px dashed;color: #597380 !important;cursor:pointer;"><?php echo ((is_array($_tmp='prd_price_multiply')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
			<span style="float: right;">
			<input name="save_products" type="submit" value='<?php echo ((is_array($_tmp='prdcat_btn_update_prices_sort')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
' />
			</span>
				</p>
			</td>
		</tr>
		</table>
		
	</form>
	<script type="text/javascript">
	var categoryID = '<?php echo $this->_tpl_vars['categoryID']; ?>
';
				<?php echo '
getLayer(\'multiply-price-handler\').onclick = function(){
	var multiply = document.getElementById(\'multiply_price_value\');
	if(multiply&&multiply.value>0){
			var inputs = getElementsByClass(\'multiply_price\',null,\'input\');
			for(var l=inputs.length-1; l>=0; l--){
				inputs[l].value = Math.round(10000*inputs[l].value*multiply.value)/10000;
			}
		}
};				
getLayer(\'choose-parentcategory-handler\').onclick = function(){if(getCountCheckGroupBox(\'select_product\')<1){alert(\'';  echo ((is_array($_tmp='prdset_related_products_select')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\');return false;}categoryTreeManager.show_tree(\'choose_parentcategory\');return false;};
getLayer(\'duplicate_products-handler\').onclick = function(){if(getCountCheckGroupBox(\'select_product\')<1){alert(\'';  echo ((is_array($_tmp='prdset_related_products_select')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\');return false;}};
var categoryTreeManager = {
	\'show_tree\': function(action){
		var url = set_query(\'?ukey=category_tree&js_action=\'+action+\'&productID=\');
		sswgt_CartManager.shop_url = "';  echo ((is_array($_tmp=@CONF_FULL_SHOP_URL)) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '";
		sswgt_CartManager.show(url, 550, 500); 	
	},
	\'hide_tree\': function(){
		sswgt_CartManager.hide();
	},
	\'actions\': {
		\'choose_parentcategory\': {
			\'onclick\': function(node){				
				if(categoryID == node.getSetting(\'categoryID\')){
					categoryTreeManager.hide_tree();
				}
				categoryTreeManager.hide_tree();
				var breadCrumbs = node.getSetting(\'name\');
				var p = node.ParentNode;
				while(p){
					breadCrumbs = p.getSetting(\'name\')+" » "+breadCrumbs;
					p = p.ParentNode;
				}
				if(window.confirm(\'';  echo ((is_array($_tmp='prdset_move_selected_to')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'+\' \'+breadCrumbs+\'?\')){
					getLayer(\'parent-category-categoryID\').value = node.getSetting(\'categoryID\');
					getLayer(\'choose-parentcategory-handler\').onclick = function(){return true;};
					getLayer(\'choose-parentcategory-handler\').click();
				}				
			}
		}
	},
	
	\'eval\': function(action, handler, node, wnd){
		
		this.actions[action][handler](node, wnd);
	}
}
'; ?>
</script>
	
	<?php else: ?>
	<p style="text-align: center"><?php echo ((is_array($_tmp='str_empty_category')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
	<?php endif; ?>

</td>
</tr>
</table>