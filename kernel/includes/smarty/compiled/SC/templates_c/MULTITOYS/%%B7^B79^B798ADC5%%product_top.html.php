<?php /* Smarty version 2.6.9, created on 2016-02-02 17:51:07
         compiled from product_top.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'product_top.html', 4, false),array('modifier', 'set_query_html', 'product_top.html', 12, false),array('modifier', 'escape', 'product_top.html', 14, false),)), $this); ?>
<?php echo '<div class=product_topview_area><h1>';  echo ((is_array($_tmp=($this->_tpl_vars['str']))) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '</h1>';  if ($this->_tpl_vars['str'] == 'str_akcia_items'):  echo '<a href="/category/akcija/">Посмотреть все акционные товары...</a>';  endif;  echo '<ul>';  $_from = $this->_tpl_vars['top']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_top']):
 echo '<li><div class=topview-pict><a href=\'';  echo ((is_array($_tmp="?productID=".($this->_tpl_vars['_top']['productID'])."&product_slug=".($this->_tpl_vars['_top']['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  echo '\'>';  if ($this->_tpl_vars['_top']['thumbnail'] || $this->_tpl_vars['_top']['picture']):  echo '<img width=160 height=120 src="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  if ($this->_tpl_vars['_top']['thumbnail']):  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_top']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  else:  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_top']['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '" alt="';  echo ((is_array($_tmp=$this->_tpl_vars['_top']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['_top']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '">';  else:  echo '<img width=153 height=117 src=\'/img/nophoto.jpg\'>';  endif;  echo '</a><div class="corner right_list color';  if ($this->_tpl_vars['_top']['akcia']):  echo '_akcia"><span></span>Акция';  if ($this->_tpl_vars['_top']['akcia_skidka'] > 0):  echo ' - ';  echo $this->_tpl_vars['_top']['akcia_skidka'];  echo '%';  endif;  echo '';  elseif ($this->_tpl_vars['_top']['sort_order']):  echo '_newitemspostup"><span></span>Новое поступление';  elseif ($this->_tpl_vars['_top']['items_sold']):  echo '_hit"><span></span>Хит продаж';  else:  echo '">';  endif;  echo '</div><p><a href=\'';  echo ((is_array($_tmp="?productID=".($this->_tpl_vars['_top']['productID'])."&product_slug=".($this->_tpl_vars['_top']['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  echo '\'>';  echo $this->_tpl_vars['_top']['name'];  echo '</a></p></div><div class=topview-price>';  echo '';  if ($this->_tpl_vars['_top']['list_price'] > 0):  echo 'старая цена&nbsp;<span class=list_price>';  echo $this->_tpl_vars['_top']['list_price_str'];  echo '</span><br><span style="color: blue;">новая цена&nbsp;</span><span class=akcia>';  echo $this->_tpl_vars['_top']['price_str'];  echo '</span>';  elseif ($this->_tpl_vars['_top']['Price']):  echo '';  echo $this->_tpl_vars['_top']['price_str'];  echo '';  endif;  echo '';  echo '</div></li>';  endforeach; endif; unset($_from);  echo '</ul><div class=clear></div></div>'; ?>