<?php /* Smarty version 2.6.9, created on 2015-10-08 20:21:45
         compiled from home.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'zakcia', 'home.html', 9, false),array('function', 'get_topproducts', 'home.html', 87, false),)), $this); ?>
<?php if ($_SESSION['cs_may_order']): ?>
                            <?php echo smarty_function_zakcia(array(), $this);?>

                                                                        
                
                                                                                                                                                                                                                                                                                                                                                                                                
    
            
                                            
            <div>
        <a href="/search?searchstring=BIGNL"><p style="
            font-size: 30px;
            color: #FD36B4;
            text-align: center;
            font-weight: 700;
            font-family: cursive;">
                Новинка! Нелопающиеся пузыри!
            </p></a>
        <video loop controls style="
                                        border-radius: 2px;
                                        margin: 15px auto;
                                        display: block;">
            <source src="/source/mylnye_puzyri.mp4" type='video/mp4; codecs="avc1.42E01E, mp4a.40.2"'/>
        </video>
    </div>
        
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