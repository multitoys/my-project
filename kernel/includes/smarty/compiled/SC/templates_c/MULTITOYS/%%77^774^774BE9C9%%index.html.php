<?php /* Smarty version 2.6.9, created on 2016-06-06 14:24:18
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'component', 'index.html', 8, false),array('function', 'welcome', 'index.html', 28, false),array('function', 'conc_tree', 'index.html', 85, false),array('function', 'slidetree', 'index.html', 88, false),array('function', 'cs_show_main_page', 'index.html', 149, false),array('modifier', 'isauthpage', 'index.html', 129, false),)), $this); ?>
<?php echo '<div id=main><div id=body><div class="header';  if ($_SESSION['log']):  echo ' inlog';  endif;  echo '">';  if ($_SESSION['log']):  echo '<div id="multi-logo-mini"><!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'logo','file' => 'logo_mini.png'), $this); echo '<!-- cpt_container_end --></div><div id="out-login"><div class="seans_out"><a href="/logout" id="logout">Выйти из сеанса</a></div><div><a href="/myaccount" id="myaccount">Личный кабинет</a></div></div>';  else:  echo '<div id="multi-logo"><!-- cpt_container_start[id=2] -->';  echo smarty_function_component(array('cpt_id' => 'logo','file' => 'logo.png'), $this); echo '<!-- cpt_container_end --></div><div id="pass-login"><a id=link_login class=blue-button href="/login/">Вход</a></div>';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@DIR_FTPLS)."/login2.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  endif;  echo '';  if ($_SESSION['cs_may_order'] > 0):  echo '<div>';  echo smarty_function_welcome(array(), $this); echo ', <span> ';  echo $_SESSION['cs_first_name'];  echo ' ';  echo $_SESSION['cs_last_name'];  echo '</span>!&nbsp;';  echo '';  if ($_SESSION['cs_unlimited'] > 0):  echo 'Вам открыт постоянный доступ!';  else:  echo 'Доступ открыт до <span>';  echo $_SESSION['cs_may_order_until'];  echo '</span>.';  endif;  echo '</div>';  endif;  echo '</div><div id="header">';  if ($_SESSION['log']):  echo '<div class="cpt_product_search"><form method="get" name="form" action="';  if ($this->_tpl_vars['conc']):  echo '';  else:  echo '/search';  endif;  echo '"><input type="text" class="input_message" rel="Поиск товаров" placeholder="Поиск товаров" name="searchstring';  if ($this->_tpl_vars['conc']):  echo '_competitors';  endif;  echo '" autocomplete="off" id="searchstring';  if ($this->_tpl_vars['conc']):  echo '_conc';  endif;  echo '"><input type="submit" value="" id=\'search_ok\' class="submit"></form><div id="live_search"><div class=search_res><div class=container></div><div class="baron__track"><div class="baron__free"><div class="baron__bar"></div></div></div></div></div></div>';  endif;  echo '';  if ($_SESSION['cs_may_order']):  echo '<a href="/cart"><div id=\'my__cart\'></div></a>';  endif;  echo '<nav class="m_nav clearfix">';  if ($_GET['already']):  echo '<div id="message-block"><span  class="error_message">Указанный пользователь уже авторизовался в системе!</span></div>';  elseif ($this->_tpl_vars['MessageBlock']):  echo '';  echo $this->_tpl_vars['MessageBlock'];  echo '';  else:  echo '<ul class="m_hmenu"><li class="cl-effect-21"><a href="/auxpage_1">О нас</a></li><li class="cl-effect-21"><a href="/auxpage_2">Сотрудничество</a></li><li class="cl-effect-21"><a href="/auxpage_bonus-programm">Бонусная программа</a></li><li class="cl-effect-21"><a href="/auxpage_contacts">Контакты</a></li><li class="cl-effect-21"><a href="/news">Новости</a></li><li class="cl-effect-21"><a href="/auxpage_zamena-braka">Замена брака</a></li></ul>';  endif;  echo '</nav></div><div id=\'main_content\'><div id="columns"><div id="left"><div class="cpt_maincolumns menu-block-wrapper">';  echo '';  echo '';  echo '';  if ($this->_tpl_vars['conc']):  echo '';  echo smarty_function_conc_tree(array('auxpage' => $this->_tpl_vars['conc']), $this); echo '';  else:  echo '';  echo '';  echo smarty_function_slidetree(array(), $this); echo '';  endif;  echo '';  if ($_SESSION['log'] && $_SESSION['cs_may_order']):  echo '<div class="predloj"><div>Курс валют:</div><div><span><b>USD = ';  echo $_SESSION['usd'];  echo ' грн</b></span></div><h3>Ваши скидки:</h3><div>Китай -&nbsp;<b>';  echo $_SESSION['cs_skidka'];  echo '%</b></div><div>Украина -&nbsp;<b>';  echo $_SESSION['cs_skidka_ua'];  echo '%</b></div><!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'currency_selection'), $this); echo '<!-- cpt_container_end --></div>';  endif;  echo '</div></div><div id="center" ';  echo '';  if ($_GET['ukey'] == 'cart'):  echo ' style="right: 0"';  elseif ($this->_tpl_vars['conc']):  echo ' class=conc';  endif;  echo '>';  if ($_GET['ukey'] == 'remind_password'):  echo '';  echo $this->_tpl_vars['MessageBlock'];  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "remind_password.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  elseif ($_SESSION['log']):  echo '';  if (@CONF_SHOW_ADD2CART != 1):  echo '<div id="parent_popup" onclick="document.getElementById(\'parent_popup\').style.display=\'none\';"><div id="popup" class="animated bounceInDown"><h1 style="text-align: center; font-size: 26px;">Уважаемые клиенты! <br />По техническим причинам<br />сайт - НЕ РАБОТАЕТ!</h1><a class="close" title="Закрыть"onclick="document.getElementById(\'parent_popup\').style.display=\'none\';">&times;</a></div></div><script type="text/javascript">var delay_popup = 200;setTimeout("document.getElementById(\'parent_popup\').style.display=\'block\'", delay_popup);</script>';  endif;  echo '';  if ($_SESSION['cs_may_order']):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '';  if (((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['ukey'])) ? $this->_run_mod_handler('isauthpage', true, $_tmp) : smarty_modifier_isauthpage($_tmp))):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '<p id = "non-acsess">Для получения доступа к полному перечню товаров,<br>Вам необходимо связаться с нашим представителем</p><br>';  echo smarty_function_component(array('cpt_id' => 'product_lists','list_id' => 'hitu','limit' => '25'), $this); echo '';  endif;  echo '';  endif;  echo '';  else:  echo '';  if (((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['ukey'])) ? $this->_run_mod_handler('isauthpage', true, $_tmp) : smarty_modifier_isauthpage($_tmp))):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '';  if ($_GET['already'] == 1):  echo '';  echo $this->_tpl_vars['MessageBlock'];  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "remind_password.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  elseif ($_GET['ukey'] == 'act_customer'):  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "login.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  else:  echo '';  echo smarty_function_cs_show_main_page(array(), $this); echo '';  endif;  echo '';  endif;  echo '';  endif;  echo '</div>';  if ($_GET['ukey'] != 'cart'):  echo '<div id="right"';  if ($this->_tpl_vars['conc']):  echo ' class=conc data-code=""><div id=lists class="cpt_maincolumns"><div id=conc_search class=cpt_product_lists><div class=conc_search_res><div class=conc_container></div><div class="baron__track"><div class="baron__free"><div class="baron__bar"></div></div></div></div></div>';  else:  echo '><div id=lists class="cpt_maincolumns"><!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'product_lists','list_id' => 'hitu','limit' => '25'), $this); echo '<!-- cpt_container_end -->';  endif;  echo '</div></div>';  endif;  echo '</div></div></div></div>'; ?>
