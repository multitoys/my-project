<?php /* Smarty version 2.6.9, created on 2015-09-21 23:18:56
         compiled from search_simple.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'search_simple.html', 6, false),array('modifier', 'translate', 'search_simple.html', 6, false),array('modifier', 'set_query_html', 'search_simple.html', 79, false),array('modifier', 'lower', 'search_simple.html', 111, false),array('modifier', 'replace', 'search_simple.html', 111, false),array('modifier', 'string_format', 'search_simple.html', 122, false),array('function', 'z_shopping_count', 'search_simple.html', 174, false),)), $this); ?>
<?php if ($this->_tpl_vars['products_to_show_count'] > 0): ?>
<div class="product_brief_head">
	<p id="cat_path"><?php echo ((is_array($_tmp=$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp='srch_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
 <b><?php echo $this->_tpl_vars['products_found']; ?>
</b> <?php echo ((is_array($_tmp='srch_products_plural')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<button class="blue-button addall" onclick="add_all2cart();">Заказать все</button>
				<?php if ($this->_tpl_vars['search_navigator']): ?><div class='simple-pagination compact-theme'><?php echo $this->_tpl_vars['search_navigator']; ?>
</div><?php endif; ?>
				<!-- Шапка таблицы -->
				    <div class=shapka>
        <table class=cs_product_info style="padding-left: 5px">
            <tbody>
            <tr>
                <td>
                    <div class="<?php if ($this->_tpl_vars['sort'] == 'name'): ?> z_sort_active <?php else: ?> z_sort_inactive <?php endif; ?>">
                        <div class="arbopr sort_name">
                            <a href="/search/?searchstring=<?php echo $this->_tpl_vars['searchstring']; ?>
&sort=name&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>"  title="Отсортировать по Наименованию">Наименование</a>
                        </div>
                        <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'name'): ?>z_sort_asc<?php else: ?>z_sort_desc<?php endif; ?>"></div>
                    </div>
                </td>
                <td width=100px>
                    <div class="<?php if ($this->_tpl_vars['sort'] == 'product_code'): ?> z_sort_active <?php else: ?> z_sort_inactive <?php endif; ?>">
                        <div class=arbopr>
                            <a href="/search/?searchstring=<?php echo $this->_tpl_vars['searchstring']; ?>
&sort=product_code&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>" title="Отсортировать по Артикулу">Артикул</a>
                        </div>
                        <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'product_code'): ?>z_sort_asc<?php else: ?>z_sort_desc<?php endif; ?>"></div>
                    </div>
                </td>
                <td width=60px>
                    <div class="<?php if ($this->_tpl_vars['sort'] == 'Bonus'): ?> z_sort_active <?php else: ?> z_sort_inactive <?php endif; ?>">
                        <div class=arbopr>
                            <a href="/search/?searchstring=<?php echo $this->_tpl_vars['searchstring']; ?>
&sort=Bonus&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>" title="Отсортировать по Баллам">Баллы</a>
                        </div>
                        <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'Bonus'): ?>z_sort_asc<?php else: ?>z_sort_desc<?php endif; ?>"></div>
                    </div>
                </td>
                <td width=80px>
                    <div class="<?php if ($this->_tpl_vars['sort'] == 'Price'): ?> z_sort_active <?php else: ?> z_sort_inactive <?php endif; ?>">
                        <div class=arbopr>
                            <a href="/search/?searchstring=<?php echo $this->_tpl_vars['searchstring']; ?>
&sort=Price&direction=<?php if ($this->_tpl_vars['direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif; ?>" title="Отсортировать по Цене">Цена</a>
                        </div>
                        <div class="<?php if ($this->_tpl_vars['direction'] == 'ASC' && $this->_tpl_vars['sort'] == 'Price'): ?>z_sort_asc<?php else: ?>z_sort_desc<?php endif; ?>"></div>
                    </div>
                </td>
                <td width=110px>
                    <div class=ost>Остаток</div>
                </td>
                <td width=65px>
                    <div class=zakaz>Заказано</div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="scroll-pane1">
    <div class='delimiter'></div>
			<?php unset($this->_sections['i1']);
$this->_sections['i1']['name'] = 'i1';
$this->_sections['i1']['loop'] = is_array($_loop=$this->_tpl_vars['products_to_show']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i1']['max'] = (int)$this->_tpl_vars['products_to_show_count'];
$this->_sections['i1']['show'] = true;
if ($this->_sections['i1']['max'] < 0)
    $this->_sections['i1']['max'] = $this->_sections['i1']['loop'];
$this->_sections['i1']['step'] = 1;
$this->_sections['i1']['start'] = $this->_sections['i1']['step'] > 0 ? 0 : $this->_sections['i1']['loop']-1;
if ($this->_sections['i1']['show']) {
    $this->_sections['i1']['total'] = min(ceil(($this->_sections['i1']['step'] > 0 ? $this->_sections['i1']['loop'] - $this->_sections['i1']['start'] : $this->_sections['i1']['start']+1)/abs($this->_sections['i1']['step'])), $this->_sections['i1']['max']);
    if ($this->_sections['i1']['total'] == 0)
        $this->_sections['i1']['show'] = false;
} else
    $this->_sections['i1']['total'] = 0;
if ($this->_sections['i1']['show']):

            for ($this->_sections['i1']['index'] = $this->_sections['i1']['start'], $this->_sections['i1']['iteration'] = 1;
                 $this->_sections['i1']['iteration'] <= $this->_sections['i1']['total'];
                 $this->_sections['i1']['index'] += $this->_sections['i1']['step'], $this->_sections['i1']['iteration']++):
$this->_sections['i1']['rownum'] = $this->_sections['i1']['iteration'];
$this->_sections['i1']['index_prev'] = $this->_sections['i1']['index'] - $this->_sections['i1']['step'];
$this->_sections['i1']['index_next'] = $this->_sections['i1']['index'] + $this->_sections['i1']['step'];
$this->_sections['i1']['first']      = ($this->_sections['i1']['iteration'] == 1);
$this->_sections['i1']['last']       = ($this->_sections['i1']['iteration'] == $this->_sections['i1']['total']);
?>
																		<?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']] != NULL): ?>
			<?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['slug']): ?>
			<?php $this->assign('_product_url', ((is_array($_tmp="?ukey=product&productID=".($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID'])."&product_slug=".($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
			<?php else: ?>
			<?php $this->assign('_product_url', ((is_array($_tmp="?ukey=product&productID=".($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp))); ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['widget']):  $this->assign('_form_action_url', "&view=noframe&external=1");  endif; ?>
			<?php $this->assign('_cnt', ''); ?>
                <table class='cs_product_info'>
                    <tbody>
                    <tr>
                        <td width='160px'>
                            <div class="visual">
                                <a <?php echo $this->_tpl_vars['target']; ?>
 href='<?php echo $this->_tpl_vars['_product_url']; ?>
'>
                                    <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['thumbnail']): ?>
                                        <img class='preview' width=160 height=120 data-pid='<?php echo @URL_PRODUCTS_PICTURES; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
' src="<?php echo @URL_PRODUCTS_PICTURES; ?>
/<?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url')); ?>
" alt="<?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" title="<?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
">
                                    <?php else: ?>
                                        <img width=153 height=117 src='/img/nophoto.jpg'>
                                    <?php endif; ?>
                                </a>
                                <div class="corner color
                                    <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['akcia']): ?>
                                         _akcia"><span></span>Акция<?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['akcia_skidka'] > 0): ?> - <?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['akcia_skidka']; ?>
%<?php endif; ?>
                                    <?php elseif ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['sort_order']): ?>
                                    _newitemspostup"><span></span>Новое поступление
                                    <?php elseif ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['items_sold']): ?>
                                    _hit"><span></span>Хит продаж
                                    <?php else: ?>
                                    ">
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class='productname'><a href='<?php echo $this->_tpl_vars['_product_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ($this->_tpl_vars['searchstring']), "<span style='color:red;'>".($this->_tpl_vars['searchstring'])."</span>") : smarty_modifier_replace($_tmp, ($this->_tpl_vars['searchstring']), "<span style='color:red;'>".($this->_tpl_vars['searchstring'])."</span>")); ?>
</a>
                                                            </div>
                        </td>
                        <td width='100px'>
                            <a href='<?php echo $this->_tpl_vars['_product_url']; ?>
'><?php echo ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')))) ? $this->_run_mod_handler('lower', true, $_tmp) : smarty_modifier_lower($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, ($this->_tpl_vars['searchstring']), "<span style='color:red;'>".($this->_tpl_vars['searchstring'])."</span>") : smarty_modifier_replace($_tmp, ($this->_tpl_vars['searchstring']), "<span style='color:red;'>".($this->_tpl_vars['searchstring'])."</span>")); ?>
</a>
                        </td>
                        <td width='60px'>
                                <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Bonus']/$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Price']>2): ?>
                                    <div style='color: red; font-size: 14px;'><b><?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Bonus'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</b></div>
                                <?php else: ?>
                                <div class="totalPrice"><?php echo ((is_array($_tmp=$this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Bonus'])) ? $this->_run_mod_handler('string_format', true, $_tmp, "%d") : smarty_modifier_string_format($_tmp, "%d")); ?>
</div>
                                <?php endif; ?>
                        </td>
                                                <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['akcia'] && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['list_price']): ?>

                            <td width='80px'>
                                <div class='akcia'><?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['PriceWithUnit']; ?>
<br /><span class='list_price'><?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['list_priceWithUnit']; ?>
</span>
                                </div>
                            </td>
                        <?php else: ?>
                            <td width='80px'>
                            <div class="price"><?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['PriceWithUnit']; ?>
</div>
                            </td>
                        <?php endif; ?>
                        <td>
                        </td>
                        <td width='175px'>
                            <?php if ($this->_tpl_vars['currencies_count'] != 0 && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Price'] > 0): ?>
							                                                                        <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ordering_available'] && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Price'] > 0 && ( @CONF_SHOW_ADD2CART == 1 ) && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['in_stock'] > 0 )): ?>
                                        <table width='175px'>
                                            <tbody>
                                            <tr>
                                                <td>
                                                    <input name="product_qty" id='qty<?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']; ?>
' class="cart_product_quantity" data-id='<?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']; ?>
' type="text" size="2" value="" title='Количество'
                                                            <?php echo '
                                                           onkeypress=\'if (event.keyCode == 13){add_2cart("#qty';  echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID'];  echo '");}\'>&nbsp;
                                                    '; ?>

                                                </td>
                                                <td style='vertical-align:middle;white-space:nowrap;'>
                                                    <div class="ostatok_div">
                                                        <?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ostatok'] == 'под заказ'): ?>
                                                            <span style='  color: red; font-style: italic;'>&nbsp;под&nbsp;заказ!</span>
                                                        <?php else: ?>
                                                            &nbsp;<?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ostatok']; ?>
&nbsp;шт.
                                                        <?php endif; ?>
                                                    </div>
                                                </td>
                                                <td>
                                                <td style='vertical-align:middle;'>
                                                    <button class="z_add_cart" onclick='add_2cart("#qty<?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']; ?>
");' type="button">
                                                        <div id="zpid_<?php echo $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']; ?>
" class='in_cart'>
                                                            <div class="animated zoomInDown">
                                                                <?php echo smarty_function_z_shopping_count(array('productID' => $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['productID']), $this);?>

                                                            </div>
                                                        </div>
                                                    </button>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    <?php elseif (@CONF_SHOW_ADD2CART == 1 && @CONF_CHECKSTOCK && ! $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['in_stock'] && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ordering_available']): ?>
                                        <div class="prd_out_of_stock"><?php echo ((is_array($_tmp='prd_out_of_stock')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
                                    <?php endif; ?>
								                            <?php endif; ?>
                        </td>
                    </tr>
                    </tbody>
                </table>
				<div class='delimiter'></div>
				<?php if ($this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ordering_available'] && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['Price'] > 0 && ( @CONF_SHOW_ADD2CART == 1 ) && ( @CONF_CHECKSTOCK == 0 || $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['in_stock'] > 0 )): ?>
				<?php elseif (@CONF_SHOW_ADD2CART == 1 && @CONF_CHECKSTOCK && ! $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['in_stock'] && $this->_tpl_vars['products_to_show'][$this->_sections['i1']['index']]['ordering_available']): ?>
				<div class="prd_out_of_stock"><?php echo ((is_array($_tmp='prd_out_of_stock')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
				<?php endif; ?>
				
				<?php endif; ?>
									<?php endfor; endif; ?>
	</div>
		<?php else: ?>
	<p><?php echo ((is_array($_tmp='srch_no_matches_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p>
<?php endif; ?>