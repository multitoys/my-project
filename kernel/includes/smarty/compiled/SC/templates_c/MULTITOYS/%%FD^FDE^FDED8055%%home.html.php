<?php /* Smarty version 2.6.9, created on 2015-10-16 11:48:24
         compiled from home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'zakcia', 'home.html', 10, false),array('function', 'get_top_ua', 'home.html', 15, false),array('function', 'get_topproducts', 'home.html', 85, false),)), $this); ?>
<?php if ($_SESSION['cs_may_order']): ?>
                        
        <?php echo smarty_function_zakcia(array(), $this);?>

    
        <div id="parent_popup" onclick="document.getElementById('parent_popup').style.display='none';">
            <div id="popup" class="animated bounceInDown">
                <h1 style="text-align: center; font-size: 26px;">Новые СКИДКИ и новые ЦЕНЫ!</h1>
                <?php echo smarty_function_get_top_ua(array('var' => 'top_ua'), $this);?>

                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "banner_top.html", 'smarty_include_vars' => array('top_ua' => $this->_tpl_vars['top_ua'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

                                <a class="close" title="Закрыть"
                   onclick="document.getElementById('parent_popup').style.display='none';">&times;</a>
            </div>
        </div>
        <script type="text/javascript">
            var delay_popup = 500;
            setTimeout("document.getElementById('parent_popup').style.display='block'", delay_popup);
        </script>
                                                                                    
    
            
                                            
                                                                                                                                                                                                                                                                            
        <?php echo smarty_function_get_topproducts(array('var' => 'akcia','limit' => 30), $this);?>

        <?php if ($this->_tpl_vars['akcia']):  $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_top.html", 'smarty_include_vars' => array('top' => $this->_tpl_vars['akcia'],'str' => 'str_akcia_items')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php else:  echo smarty_function_get_topproducts(array('var' => 'items_sold','limit' => 30), $this);?>

            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "product_top.html", 'smarty_include_vars' => array('top' => $this->_tpl_vars['items_sold'],'str' => 'str_items_sold_items')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
        <?php endif; ?>

            <?php endif; ?>