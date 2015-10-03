<?php /* Smarty version 2.6.9, created on 2015-09-21 23:17:29
         compiled from product_info.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'component', 'product_info.html', 5, false),)), $this); ?>
<!-- <div class="scroll-pane1"> -->
<table style="width: 100%; padding: 0px;">
  <tr>
    <td colspan="2">
      <div style="font-style:italic; color:#000099;"><!-- cpt_container_start --><?php echo smarty_function_component(array('cpt_id' => 'product_name','overridestyle' => ''), $this);?>

                <!-- cpt_container_end --></div>
    </td>
  </tr>
  <tr>
    <td id="prddeatailed_container">
      <?php echo smarty_function_component(array('cpt_id' => 'product_images'), $this);?>

    </td>
    <td class="align_top">
      <div class="pr_info_block"><!-- cpt_container_start --><?php echo smarty_function_component(array('cpt_id' => 'product_price','overridestyle' => ':41rxw3'), $this);?>
<br/><?php echo smarty_function_component(array('cpt_id' => 'product_add2cart_button','request_product_count' => 'request_product_count','overridestyle' => ':qa8pav'), $this); echo smarty_function_component(array('cpt_id' => 'product_params_selectable','overridestyle' => ':2cbg4t'), $this);?>
<!-- cpt_container_end -->
      <div style="padding-left:30px; padding-top:10px; color:#000099;"><!-- cpt_container_start --><?php echo smarty_function_component(array('cpt_id' => 'product_description','overridestyle' => ''), $this);?>
<!-- cpt_container_end --></div>
      <div style="padding-left:30px; padding-top:10px; color:#000099;"><!-- cpt_container_start --><!-- cpt_container_end --></div>
      <div style="padding-left:30px; padding-top:10px; color:#000099;"><!-- cpt_container_start --><?php echo smarty_function_component(array('cpt_id' => 'product_params_selectable','overridestyle' => ''), $this);?>
<!-- cpt_container_end --></div>
      <!-- cpt_container_start --><?php echo smarty_function_component(array('cpt_id' => 'product_discuss_link','overridestyle' => ''), $this);?>
<!-- cpt_container_end --></div>
    </td>
  </tr>
</table>
<!-- </div> -->