<?php /* Smarty version 2.6.9, created on 2016-01-26 11:26:44
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'component', 'index.html', 6, false),array('function', 'welcome', 'index.html', 26, false),array('function', 'conc_tree', 'index.html', 85, false),array('function', 'newtree', 'index.html', 95, false),array('function', 'cs_show_main_page', 'index.html', 157, false),array('modifier', 'isauthpage', 'index.html', 137, false),)), $this); ?>
<?php echo '<div>';  if ($_SESSION['log']):  echo '<div id="multi-logo-mini"><!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'logo','file' => 'logo_mini.png'), $this); echo '<!-- cpt_container_end --></div><div id="out-login"><div class="seans_out"><a href="/logout" id="logout">Выйти из сеанса</a></div><div><a href="/myaccount" id="myaccount">Личный кабинет</a></div></div>';  else:  echo '<div id="multi-logo"><!-- cpt_container_start[id=2] -->';  echo smarty_function_component(array('cpt_id' => 'logo','file' => 'logo.png'), $this); echo '<!-- cpt_container_end --></div><div id="pass-login">';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => (@DIR_FTPLS)."/login2.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '</div>';  endif;  echo '<div class="header';  if ($_SESSION['log']):  echo ' inlog';  endif;  echo '">';  if ($_SESSION['cs_may_order'] > 0):  echo '<div>';  echo smarty_function_welcome(array(), $this); echo ', <span> ';  echo $_SESSION['cs_first_name'];  echo ' ';  echo $_SESSION['cs_last_name'];  echo '</span>!&nbsp;';  echo '';  if ($_SESSION['cs_unlimited'] > 0):  echo 'Вам открыт постоянный доступ!';  else:  echo 'Доступ открыт до <span>';  echo $_SESSION['cs_may_order_until'];  echo '</span>.';  endif;  echo '</div>';  endif;  echo '</div><div id="header">';  if ($_SESSION['log']):  echo '<div class="cpt_product_search"><form method="get" name="form"action="';  if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo '';  else:  echo '/search';  endif;  echo '"><input type="text" class="input_message" rel="Поиск товаров" value="Поиск товаров"name="searchstring';  if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo '_competitors';  endif;  echo '"autocomplete="off"id="searchstring';  if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo '_conc';  endif;  echo '"><input type="submit" value="" id=\'search_ok\' class="submit"></form>';  echo '';  echo '';  echo '';  echo '';  echo '';  echo '<div id="live_search"></div></div>';  endif;  echo '';  echo '';  if ($_SESSION['cs_may_order']):  echo '<a href="/cart"><div id=\'my__cart\'></div></a>';  endif;  echo '<nav class="m_nav clearfix">';  if ($_GET['already']):  echo '<div id="message-block"><span  class="error_message">Указанный пользователь уже авторизовался в системе!</span></div>';  elseif ($this->_tpl_vars['MessageBlock']):  echo '';  echo $this->_tpl_vars['MessageBlock'];  echo '';  else:  echo '<ul class="m_hmenu"><li class="cl-effect-21"><a href="/auxpage_1">О нас</a></li><li class="cl-effect-21"><a href="/auxpage_2">Сотрудничество</a></li><li class="cl-effect-21"><a href="/auxpage_bonus-programm">Бонусная программа</a></li><li class="cl-effect-21"><a href="/auxpage_contacts">Контакты</a></li><li class="cl-effect-21"><a href="/news">Новости</a></li><li class="cl-effect-21"><a href="/auxpage_zamena-braka">Замена брака</a></li></ul>';  endif;  echo '</nav></div><div id=\'main_content\'><div id="columns"><div id="left"><div class="cpt_maincolumns">';  echo '';  echo '';  echo '';  if ($_GET['ukey'] == 'auxpage_divoland'):  echo '';  echo smarty_function_conc_tree(array('auxpage' => 'divoland'), $this); echo '';  elseif ($_GET['ukey'] == 'auxpage_mixtoys'):  echo '';  echo smarty_function_conc_tree(array('auxpage' => 'mixtoys'), $this); echo '';  elseif ($_GET['ukey'] == 'auxpage_dreamtoys'):  echo '';  echo smarty_function_conc_tree(array('auxpage' => 'dreamtoys'), $this); echo '';  elseif ($_GET['ukey'] == 'auxpage_kindermarket'):  echo '';  echo smarty_function_conc_tree(array('auxpage' => 'kindermarket'), $this); echo '';  elseif ($_GET['ukey'] == 'auxpage_grandtoys'):  echo '';  echo smarty_function_conc_tree(array('auxpage' => 'grandtoys'), $this); echo '';  else:  echo '';  echo smarty_function_newtree(array('haspopup' => $this->_tpl_vars['haspopup']), $this); echo '';  endif;  echo '';  if ($_SESSION['cs_may_order']):  echo '';  if ($_SESSION['log']):  echo '<div class="predloj"><div>Курс валют:</div><div><span><b>USD = ';  echo $_SESSION['usd'];  echo ' грн</b></span></div>';  echo '<h3>Ваши скидки:</h3><div>Китай -&nbsp;<b>';  echo $_SESSION['cs_skidka'];  echo '%</b></div><div>Украина -&nbsp;<b>';  echo $_SESSION['cs_skidka_ua'];  echo '%</b></div>';  echo '<!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'currency_selection'), $this); echo '<!-- cpt_container_end --></div>';  endif;  echo '';  endif;  echo '</div></div><div id="center"';  if ($_GET['ukey'] == 'cart'):  echo 'style="right: 0"';  elseif ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo 'class=conc';  endif;  echo '>';  if ($_SESSION['log']):  echo '';  echo '';  if ($_SESSION['cs_may_order']):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '';  if (((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['ukey'])) ? $this->_run_mod_handler('isauthpage', true, $_tmp) : smarty_modifier_isauthpage($_tmp))):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '<p id = "non-acsess">Для получения доступа к полному перечню товаров,<br>Вам необходимо связаться с нашим представителем</p><br>';  echo smarty_function_component(array('cpt_id' => 'product_lists','list_id' => 'hitu','limit' => '25'), $this); echo '';  endif;  echo '';  endif;  echo '';  else:  echo '';  if (((is_array($_tmp=$this->_tpl_vars['CurrentDivision']['ukey'])) ? $this->_run_mod_handler('isauthpage', true, $_tmp) : smarty_modifier_isauthpage($_tmp))):  echo '';  echo smarty_function_component(array('cpt_id' => 'maincontent'), $this); echo '';  else:  echo '';  if ($_GET['already'] == 1):  echo '';  echo $this->_tpl_vars['MessageBlock'];  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "remind_password.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  elseif ($_GET['ukey'] == 'act_customer'):  echo '';  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "login.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  echo '';  else:  echo '';  echo smarty_function_cs_show_main_page(array(), $this); echo '';  endif;  echo '';  endif;  echo '';  endif;  echo '</div>';  if ($_GET['ukey'] != 'cart'):  echo '<div id="right"';  if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo 'class=conc';  endif;  echo '><div class="cpt_maincolumns">';  if ($_GET['ukey'] == 'auxpage_divoland' || $_GET['ukey'] == 'auxpage_mixtoys' || $_GET['ukey'] == 'auxpage_dreamtoys' || $_GET['ukey'] == 'auxpage_kindermarket' || $_GET['ukey'] == 'auxpage_grandtoys'):  echo '';  else:  echo '<!-- cpt_container_start -->';  echo smarty_function_component(array('cpt_id' => 'product_lists','list_id' => 'hitu','limit' => '10'), $this); echo '<!-- cpt_container_end -->';  endif;  echo '</div></div>';  endif;  echo '</div></div></div>'; ?>
