<?php /* Smarty version 2.6.9, created on 2016-02-02 17:44:41
         compiled from product_list.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', 'product_list.html', 9, false),array('modifier', 'escape', 'product_list.html', 10, false),)), $this); ?>
<?php echo '<div class=product_topview_area><ul>';  $_from = $this->_tpl_vars['__products']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['_product']):
 echo '';  if ($this->_tpl_vars['_product']['thumbnail'] || $this->_tpl_vars['_product']['picture']):  echo '<li><div class=topview-pict>';  if ($this->_tpl_vars['_product']['thumbnail'] || $this->_tpl_vars['_product']['picture']):  echo '<a href=\'';  echo ((is_array($_tmp="?productID=".($this->_tpl_vars['_product']['productID'])."&product_slug=".($this->_tpl_vars['_product']['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  echo '\'><img width=160 height=120 src="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  if ($this->_tpl_vars['_product']['thumbnail']):  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_product']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  else:  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_product']['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '" alt="';  echo ((is_array($_tmp=$this->_tpl_vars['_product']['name_ru'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['_product']['name_ru'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '"></a>';  else:  echo '<img width=153 height=117 src=\'/img/nophoto.jpg\'>';  endif;  echo '<div class="corner right_list color_hit"><span></span>Хит продаж';  echo '';  echo '';  echo '</div><p><a href=\'';  echo ((is_array($_tmp="?productID=".($this->_tpl_vars['_product']['productID'])."&product_slug=".($this->_tpl_vars['_product']['slug']))) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp));  echo '\'>';  echo $this->_tpl_vars['_product']['name_ru'];  echo '</a></p></div>';  echo '';  if ($_SESSION['cs_may_order']):  echo '<div class=topview-price>';  if ($this->_tpl_vars['_product']['list_price'] > 0):  echo 'старая цена&nbsp;<span class=list_price>';  echo $this->_tpl_vars['_product']['list_price_str'];  echo '</span>';  endif;  echo '</div><div class=topview-price>';  if ($this->_tpl_vars['_product']['list_price'] > 0):  echo '<span style="color: blue;">новая цена&nbsp;</span>';  endif;  echo '';  if ($this->_tpl_vars['_product']['Price']):  echo '<span class=price-right-list>';  echo $this->_tpl_vars['_product']['price_str'];  echo '</span>';  endif;  echo '</div>';  endif;  echo '';  echo '</li>';  endif;  echo '';  endforeach; endif; unset($_from);  echo '</ul></div>'; ?>