<?php /* Smarty version 2.6.9, created on 2015-09-21 23:17:29
         compiled from product_images.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'escape', 'product_images.html', 22, false),array('modifier', 'translate', 'product_images.html', 97, false),array('function', 'counter', 'product_images.html', 59, false),)), $this); ?>
<?php if (! $this->_tpl_vars['CPT_CONSTRUCTOR_MODE']): ?>
    <?php echo '<script type="text/javascript" src="';  echo @URL_ROOT;  echo '/3rdparty/highslide/highslide.js"></script><script type="text/javascript">hs.graphicsDir = \'';  echo @URL_ROOT;  echo '/3rdparty/highslide/graphics/\';hs.registerOverlay('; ?>{<?php echo 'thumbnailId: null,overlayId: \'controlbar\',position: \'top right\',hideOnMouseOut: true'; ?>}<?php echo ');hs.outlineType = \'rounded-white\';</script>';  if ($this->_tpl_vars['PAGE_VIEW'] != 'mobile' && $this->_tpl_vars['product_info']['picture']):  echo '<div style="text-align:center;width: ';  echo @CONF_PRDPICT_STANDARD_SIZE+10;  echo 'px;"><a name="anch_current_picture"></a><div style="position: relative;"';  echo '>';  if (! $this->_tpl_vars['printable_version'] && $this->_tpl_vars['product_info']['big_picture'] && ( $this->_tpl_vars['product_info']['picture_width'] > @CONF_PRDPICT_STANDARD_SIZE || $this->_tpl_vars['product_info']['picture_height'] > @CONF_PRDPICT_STANDARD_SIZE )):  echo '<a target="_blank" onclick="return hs.expand(this)" href=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  if ($this->_tpl_vars['product_info']['big_picture']):  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['big_picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  else:  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '\' img_width="';  echo $this->_tpl_vars['product_info']['picture_width'];  echo '" img_height="';  echo $this->_tpl_vars['product_info']['picture_height'];  echo '"><img id=\'img-current_picture\' border=\'0\' src="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" alt="';  echo ((is_array($_tmp=$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" /></a><div class="corner color';  if ($this->_tpl_vars['product_info']['akcia']):  echo '_akcia"><span></span>Акция';  if ($this->_tpl_vars['product_info']['akcia_skidka'] > 0):  echo ' - ';  echo $this->_tpl_vars['product_info']['akcia_skidka'];  echo '%';  endif;  echo '';  elseif ($this->_tpl_vars['product_info']['eproduct_available_days'] > 5):  echo '_superprice"><span></span>Суперцена!';  elseif ($this->_tpl_vars['product_info']['sort_order']):  echo '_newitemspostup"><span></span>Новое поступление';  elseif ($this->_tpl_vars['product_info']['items_sold']):  echo '_hit"><span></span>Хит продаж';  else:  echo '">';  endif;  echo '</div>';  else:  echo '<div class="corner color';  if ($this->_tpl_vars['product_info']['akcia']):  echo '_akcia"><span></span>Акция';  if ($this->_tpl_vars['product_info']['akcia_skidka'] > 0):  echo ' - ';  echo $this->_tpl_vars['product_info']['akcia_skidka'];  echo '%';  endif;  echo '';  elseif ($this->_tpl_vars['product_info']['sort_order']):  echo '_newitemspostup"><span></span>Новое поступление';  elseif ($this->_tpl_vars['product_info']['items_sold']):  echo '_hit"><span></span>Хит продаж';  else:  echo '">';  endif;  echo '</div><img id=\'img-current_picture\' border=\'0\' src="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['picture'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" alt="';  echo ((is_array($_tmp=$this->_tpl_vars['page_title'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" />';  endif;  echo '</div>';  if ($this->_tpl_vars['all_product_pictures']):  echo '<table cellpadding="3" align="center" id="box_product_thumbnails">';  $_from = $this->_tpl_vars['all_product_pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['frpict'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['frpict']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_picture']):
        $this->_foreach['frpict']['iteration']++;
 echo '';  if ($this->_tpl_vars['_picture']['photoID'] != $this->_tpl_vars['product_info']['photoID']):  echo '';  echo smarty_function_counter(array('name' => '_pict_num','assign' => '_pict_num'), $this); echo '';  if (( $this->_tpl_vars['_pict_num']-1 ) % 2 == 0):  echo '<tr>';  endif;  echo '<td align="center" style="width: ';  echo @CONF_PRDPICT_THUMBNAIL_SIZE;  echo 'px;">';  if (! $this->_tpl_vars['printable_version'] && ( $this->_tpl_vars['_picture']['width'] > @CONF_PRDPICT_THUMBNAIL_SIZE || $this->_tpl_vars['_picture']['height'] > @CONF_PRDPICT_THUMBNAIL_SIZE )):  echo '<a onclick="return hs.expand(this)" href=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  if ($this->_tpl_vars['_picture']['enlarged']):  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['enlarged'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  else:  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '\' img_width="';  echo $this->_tpl_vars['_picture']['width'];  echo '" img_height="';  echo $this->_tpl_vars['_picture']['height'];  echo '" img_enlarged="';  if ($this->_tpl_vars['_picture']['enlarged']):  echo '';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['enlarged'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '" img_picture="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '" target="_blank"><img src=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '\' border=\'0\' alt="';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" style="width: ';  echo @CONF_PRDPICT_THUMBNAIL_SIZE;  echo 'px;"/></a>';  else:  echo '<img src=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '\' border=\'0\' alt="';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" title="';  echo ((is_array($_tmp=$this->_tpl_vars['product_info']['name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '" />';  endif;  echo '</td>';  if (( $this->_tpl_vars['_pict_num'] ) % 2 == 0):  echo '</tr>';  endif;  echo '';  endif;  echo '';  endforeach; endif; unset($_from);  echo '';  if (( $this->_tpl_vars['_pict_num']+1 ) % 2 == 0):  echo '</tr>';  endif;  echo '</table>';  endif;  echo '';  endif;  echo '';  if ($this->_tpl_vars['PAGE_VIEW'] == 'mobile' && $this->_tpl_vars['m_all_product_pictures']):  echo '<table cellpadding="3" id="box_product_thumbnails">';  $_from = $this->_tpl_vars['m_all_product_pictures']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['frpict'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['frpict']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['_picture']):
        $this->_foreach['frpict']['iteration']++;
 echo '<tr><td align="center"><a href=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  if ($this->_tpl_vars['_picture']['enlarged']):  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['enlarged'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  else:  echo '';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '\' img_width="';  echo $this->_tpl_vars['_picture']['width'];  echo '" img_height="';  echo $this->_tpl_vars['_picture']['height'];  echo '" img_enlarged="';  if ($this->_tpl_vars['_picture']['enlarged']):  echo '';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['enlarged'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '';  endif;  echo '" img_picture="';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['filename'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '" target="_blank"><img src=\'';  echo @URL_PRODUCTS_PICTURES;  echo '/';  echo ((is_array($_tmp=$this->_tpl_vars['_picture']['thumbnail'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'url') : smarty_modifier_escape($_tmp, 'url'));  echo '\' border=\'0\' /></a></td></tr>';  endforeach; endif; unset($_from);  echo '</table>';  endif;  echo '</div><div id="controlbar" class="highslide-overlay controlbar"><a href="#" class="previous" onclick="return hs.previous(this)"></a><a href="#" class="next" onclick="return hs.next(this)"></a><a href="#" class="close" onclick="return hs.close(this)"></a></div>';  else:  echo '<div style="text-align:center"><img border=0 src="';  echo @URL_DEMOPRD_IMAGES;  echo '/picture1.jpg" alt="';  echo ((is_array($_tmp=((is_array($_tmp='demoprd_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)))) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'));  echo '"><br><table align="center"><tr><td><img src="';  echo @URL_IMAGES;  echo '/enlarge.gif"></td><td><a class="olive" href="#">';  echo ((is_array($_tmp='str_enlarge_picture')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '</a></td></tr></table></div>'; ?>

<?php endif; ?>