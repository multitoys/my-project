<?php /* Smarty version 2.6.9, created on 2015-09-22 17:51:14
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/open_list.html */ ?>
<?php echo '
<style>

.zu_body {
	padding: 10px;
}

.zu_list td{

}

</style>
'; ?>


<div class="zu_body">
	<h3>Список пользователей с открытым доступом</h3>
	<table cellpadding=5 cellspacing=5 class="zu_list">
		<tr style="background-color: #e5e5e5">
			<td>Имя</td>
			<td>Логин</td>
			<td>Email</td>		
		</tr>
	<?php $_from = $this->_tpl_vars['o_users']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['foo']):
?>
		<tr>
			<td><a href="/index.php?ukey=user_info&userID=<?php echo $this->_tpl_vars['foo']['customerID']; ?>
&rdid=22"><?php echo $this->_tpl_vars['foo']['last_name']; ?>
 <?php echo $this->_tpl_vars['foo']['first_name']; ?>
</a></td>
			<td><a href="/index.php?ukey=user_info&userID=<?php echo $this->_tpl_vars['foo']['customerID']; ?>
&rdid=22"><?php echo $this->_tpl_vars['foo']['Login']; ?>
</a></td>
			<td><a href="/index.php?ukey=user_info&userID=<?php echo $this->_tpl_vars['foo']['customerID']; ?>
&rdid=22"><?php echo $this->_tpl_vars['foo']['Email']; ?>
</a></td>
		</tr>
	<?php endforeach; endif; unset($_from); ?>
	</table>
</div>