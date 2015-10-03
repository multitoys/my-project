<?php /* Smarty version 2.6.9, created on 2015-09-23 10:06:12
         compiled from backend/order_editor/states.html */ ?>
<select name=<?php echo $this->_tpl_vars['name']; ?>
 <?php if ($this->_tpl_vars['class']): ?>class="<?php echo $this->_tpl_vars['class']; ?>
"<?php endif; ?> <?php if ($this->_tpl_vars['id']): ?>id="<?php echo $this->_tpl_vars['id']; ?>
"<?php endif; ?>>
<?php $_from = $this->_tpl_vars['states']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['state']):
?>
    <option value="<?php echo $this->_tpl_vars['state']['zoneID']; ?>
" <?php if ($this->_tpl_vars['state']['zoneID'] == $this->_tpl_vars['selected']): ?>selected="selected"<?php endif; ?>><?php echo $this->_tpl_vars['state']['zone_name']; ?>
</option>
<?php endforeach; endif; unset($_from); ?>
</select>