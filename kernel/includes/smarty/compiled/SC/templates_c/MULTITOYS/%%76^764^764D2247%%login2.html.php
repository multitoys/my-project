<?php /* Smarty version 2.6.9, created on 2015-10-01 17:56:33
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/frontend/login2.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query_html', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/frontend/login2.html', 2, false),array('modifier', 'escape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/frontend/login2.html', 9, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/frontend/login2.html', 14, false),)), $this); ?>
<div id="log2div">
    <form action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
" method="post" name="authform">
        <div class="form_login2">
            <input type="hidden" name="enter" value="<?php echo $this->_tpl_vars['enter']; ?>
">
            <input type="hidden" name="GEO_REGION">
            <input type="hidden" name="GEO_CITY">
            <input type="hidden" name="GEO_ISP">
            <input type="text" name="user_login" id="ln_user_login" placeholder="Логин"
                   value="<?php echo ((is_array($_tmp=$this->_tpl_vars['user_login'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" size="16" autofocus>
            <br>
            <input name="user_pw" id="ln_user_pw" placeholder="Пароль" type="password" size="10">
            <input class="login-button blue-button" type="submit" value='вход'/><br>
            <span class="log2">или </span>
            <a href="<?php echo ((is_array($_tmp='?ukey=register')) ? $this->_run_mod_handler('set_query_html', true, $_tmp) : smarty_modifier_set_query_html($_tmp)); ?>
"><?php echo ((is_array($_tmp='pgn_register')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>
        </div>
    </form>
    </div>