<?php /* Smarty version 2.6.9, created on 2015-10-18 21:40:29
         compiled from backend/user_contact.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/user_contact.html', 59, false),array('modifier', 'escape', 'backend/user_contact.html', 67, false),array('modifier', 'date_format', 'backend/user_contact.html', 124, false),array('modifier', 'transcape', 'backend/user_contact.html', 222, false),)), $this); ?>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/jquery.1.4.2.js"></script>

<?php echo '
<SCRIPT language="JavaScript">

  function givetime(cid, time_h)
  {
    var url = \'/popup/prolong.php?id=\'+cid+\'&time=\'+time_h;
    $.get(url, function(data){
      alert(data);
    });
  }

    function givevip(cid, time_h)
  {
    var url = \'/popup/vip.php?id=\'+cid+\'&time=\'+time_h;
    $.get(url, function(data){
      alert(data);
    });
  }

  function givesbros(cid, time_h)
  {
    var url = \'/popup/sbros.php?id=\'+cid+\'&time=\'+time_h;
    $.get(url, function(data){
      alert(data);
    });
  }
  
  function ZCheckSkidka() {
  	var skidka = $(\'#skidka_inp\').val();
  	if(skidka && Number(skidka)<0) {
  		skidka = skidka * -1;
  		$(\'#skidka_inp\').val(skidka);
  	}
    var skidka_ua = $(\'#skidka_ua_inp\').val();
  	if(skidka_ua && Number(skidka_ua)<0) {
        skidka_ua = skidka_ua * -1;
  		$(\'#skidka_ua_inp\').val(skidka_ua);
  	}
  }

  function ZHandleCheckBox(cmp) {
	if(cmp.id == \'ignore_skidka_inp\' && cmp.checked) {
		$(\'#is_special_price_inp\').removeAttr("checked");
	}
	if(cmp.id == \'is_special_price_inp\' && cmp.checked) {
		$(\'#ignore_skidka_inp\').removeAttr("checked");
	}
  }

</SCRIPT>
'; ?>


<form method="post" name="EditCustomerForm" onsubmit="ZCheckSkidka();">
<input type="hidden" name="action" value="save_contact_info" />
<table class="address_form" cellspacing="0" cellpadding="3">
	<tr>
		<td><strong><?php echo ((is_array($_tmp='usr_custinfo_login')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td><?php if ($this->_tpl_vars['customerInfo']['Login'] != ''):  echo $this->_tpl_vars['customerInfo']['Login'];  else: ?><i><?php echo ((is_array($_tmp='msg_no_customer_login')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</i><?php endif; ?></td>
	</tr>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td><strong><?php echo ((is_array($_tmp='usr_custinfo_first_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td>
            <span id="first_name_txt"><?php echo $this->_tpl_vars['customerInfo']['first_name']; ?>
</span>
            <input class="txt_or_sel" type="text" name="ci[first_name]" id="first_name_inp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customerInfo']['first_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
        </td>
	</tr>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td><strong><?php echo ((is_array($_tmp='usr_custinfo_last_name')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td>
            <span id="last_name_txt"><?php echo $this->_tpl_vars['customerInfo']['last_name']; ?>
</span>
            <input class="txt_or_sel" type="text" name="ci[last_name]" id="last_name_inp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customerInfo']['last_name'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
        </td>
	</tr>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td><strong><?php echo ((is_array($_tmp='usr_custinfo_email')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td>
            <?php if (@CONF_BACKEND_SAFEMODE == 0): ?>
                <span id="email_txt"><?php echo $this->_tpl_vars['customerInfo']['Email']; ?>
</span>
                <input class="txt_or_sel" type="text" name="ci[Email]" id="email_inp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customerInfo']['Email'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
            <?php else: ?>
                <?php echo ((is_array($_tmp='msg_safemode_info_blocked')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

            <?php endif; ?>
        </td>
	</tr>
    <?php $_from = $this->_tpl_vars['reg_fields']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['reg_fld']):
?>
    <tr><td colspan="2"><div class="divider_grey"></div></td></tr>
    <tr>
        <td><strong><?php echo $this->_tpl_vars['reg_fld']['reg_field_name']; ?>
</strong></td>
        <td>
            <span id="rf_<?php echo $this->_tpl_vars['reg_fld']['reg_field_ID']; ?>
_txt"><?php echo $this->_tpl_vars['reg_fld']['reg_field_value']; ?>
</span>
            <input class="txt_or_sel" type="text" name="ci[reg_field][<?php echo $this->_tpl_vars['reg_fld']['reg_field_ID']; ?>
]" id="rf_<?php echo $this->_tpl_vars['reg_fld']['reg_field_ID']; ?>
_txt" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['reg_fld']['reg_field_value'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
        </td>
    </tr>
    <?php endforeach; endif; unset($_from); ?>

	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td valign="top"><strong><?php echo ((is_array($_tmp='usrreg_subscribe_for_blognews')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td>
            <span id="subscribed4news_txt"><?php if ($this->_tpl_vars['customerInfo']['subscribed4news']):  echo ((is_array($_tmp='str_answer_yes')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  else:  echo ((is_array($_tmp='str_answer_no')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?></span>
			<input id="subscribed4news_inp" type="checkbox" <?php if ($this->_tpl_vars['customerInfo']['subscribed4news']): ?>checked<?php endif; ?> name = 'ci[subscribed4news]' value='1' style="display: none;" />
		</td>
	</tr>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>

	<tr>
		<td valign="top">
			<strong>Баллы</strong>
		</td>
		<td>
            <span id="custgroupID_txt"><?php echo $this->_tpl_vars['customerInfo']['1C']; ?>
</span>

		</td>
	</tr>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td valign="top"><strong><?php echo ((is_array($_tmp='usr_account_state')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td>
			<?php if (( $this->_tpl_vars['customerInfo']['may_order_until'] > ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")) ) || $this->_tpl_vars['customerInfo']['unlimited_order']): ?>
				<span style="color:green"><?php echo ((is_array($_tmp='usr_account_activated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
			<?php else: ?>
				<span style="color:red; font-weight:bold"><?php echo ((is_array($_tmp='usr_account_notactivated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span>
			<?php endif; ?>
            <select class="txt_or_sel" name="ci[activated]" id="activated_inp" style="display: none">
                <option value="0" <?php if ($this->_tpl_vars['customerInfo']['ActivationCode'] != ''): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='usr_account_notactivated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
                <option value="1" <?php if ($this->_tpl_vars['customerInfo']['ActivationCode'] == ''): ?>selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp='usr_account_activated')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</option>
            </select>
		</td>
	</tr>

	<tr>
		<td><strong>Скидка Китай, %</strong></td>
		<td>
            <span id="skidka_txt"><?php echo $this->_tpl_vars['customerInfo']['skidka']; ?>
</span>
            <input class="txt_or_sel" type="text" name="ci[skidka]" id="skidka_inp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customerInfo']['skidka'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
        </td>
	</tr>

	<tr>
		<td><strong>Скидка Украина, %</strong></td>
		<td>
            <span id="skidka_ua_txt"><?php echo $this->_tpl_vars['customerInfo']['skidka_ua']; ?>
</span>
            <input class="txt_or_sel" type="text" name="ci[skidka_ua]" id="skidka_ua_inp" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['customerInfo']['skidka_ua'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" style="display: none;" />
        </td>
	</tr>

											        	
											        	
		<td valign="top">
			<strong>Авторизация</strong>
		</td>
		<td>
			<span id="custgroupID_txt"><?php echo $this->_tpl_vars['customerInfo']['logged']; ?>
</span>
		</td>
	</tr>
	<tr>
		<td valign="top">
			<strong>Доступ к заказам до</strong>
		</td>
		<td>
			<span style="color:blue; font-weight:bold"><?php echo $this->_tpl_vars['customerInfo']['may_order_until']; ?>
</span>
		</td><td><?php echo ((is_array($_tmp=time())) ? $this->_run_mod_handler('date_format', true, $_tmp, "%Y-%m-%d %H:%M:%S") : smarty_modifier_date_format($_tmp, "%Y-%m-%d %H:%M:%S")); ?>
</td>
	</tr>	<tr>
		<td valign="top">
			<strong>Постоянный доступ</strong>
		</td>
		<td>
			<span style="color:red; font-weight:bold"><?php echo $this->_tpl_vars['customerInfo']['unlimited_order']; ?>
</span>
		</td>
	</tr>
	<tr>
		<td></td>
		<td>
	    <button type='button' style='width:160px;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 2);'>Предоставить на 2 часа</button><br>
	    <button type='button' style='width:160px;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 24);'>Предоставить на сутки</button><br>
	    <button type='button' style='width:160px;color: blue;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 72);'>Предоставить на 3 дня</button><br>
	    <button type='button' style='width:160px;color: red;font-weight: bold;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, -1);'>Закрыть  доступ</button><br>
		<button type='button' style='width:160px;color: purple;' onclick='givesbros(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 555);'>Сбросить авторизацию</button><br>
		</td>
		<?php if ($this->_tpl_vars['is_right'] == 1): ?>
		<td>	
	    <button type='button' style='width:220px;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 999);'>Предоставить постоянный доступ</button><br>
<!-- 	    <button type='button' style='width:220px;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 8760);'>Предоставить постоянный доступ</button><br> -->
	    <button type='button' style='width:220px;color: red;font-weight: bold;' onclick='givetime(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 998);'>Отменить постоянный доступ</button><br>
		<button type='button' style='width:220px;' onclick='givevip(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 777);'>Предоставить VIP-доступ</button><br>
		<button type='button' style='width:220px;' onclick='givevip(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 888);'>Предоставить SuperVIP-доступ</button><br>
	    <button type='button' style='width:220px;color: red;font-weight: bold;' onclick='givevip(<?php echo $this->_tpl_vars['customerInfo']['customerID']; ?>
, 666);'>Отменить VIP-доступ</button><br>  
		</td>
	  <?php endif; ?>
	</tr>

	<?php if ($this->_tpl_vars['customerInfo']['ActivationCode']): ?>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr>
		<td><strong><?php echo ((is_array($_tmp='usr_account_activation_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</strong></td>
		<td><?php echo $this->_tpl_vars['customerInfo']['ActivationCode']; ?>
</td>
	</tr>
	<?php endif; ?>
	<tr><td colspan="2"><div class="divider_grey"></div></td></tr>
	<tr id="form_buttons" style="display: none;">
		<td></td>
		<td>
			<input value="<?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('transcape', true, $_tmp) : smarty_modifier_transcape($_tmp)); ?>
" name="save" type="submit" />
            <button type="button" onClick="hideEditForm();"><?php echo ((is_array($_tmp='btn_cancel')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</button>
					</td>
	</tr>
</table>
</form>

<script type="text/javascript" language="JavaScript">
<?php echo '

function showEditForm()
{
    var frm = document.forms[\'EditCustomerForm\'];
    var j = 0;
    for(i=0; i<frm.elements.length; i++)
    {
        if(frm.elements[i].type == \'submit\' || frm.elements[i].type == \'button\' || frm.elements[i].type == \'hidden\') continue;
        var txt_id = frm.elements[i].id.replace(\'_inp\',\'_txt\');
        frm.elements[i].style.display = \'\';
        if(document.getElementById(txt_id) && document.getElementById(txt_id).style) document.getElementById(txt_id).style.display = \'none\';
        if(j % 2 == 0)
        {
            frm.elements[i].parentNode.parentNode.style.backgroundColor = \'#FAFAE7\';
        };
        j++;
    };
    document.getElementById(\'form_buttons\').style.display = \'\';
    document.getElementById(\'elink\').style.display = \'none\';
}

function hideEditForm()
{
    var frm = document.forms[\'EditCustomerForm\'];
    for(i=0; i<frm.elements.length; i++)
    {
        if(frm.elements[i].type == \'submit\' || frm.elements[i].type == \'button\' || frm.elements[i].type == \'hidden\') continue;
        var txt_id = frm.elements[i].id.replace(\'_inp\',\'_txt\');
        frm.elements[i].style.display = \'none\';
        document.getElementById(txt_id).style.display = \'\';
        frm.elements[i].parentNode.parentNode.style.backgroundColor = \'#FFFFFF\';
    }
    document.getElementById(\'form_buttons\').style.display = \'none\';
    document.getElementById(\'elink\').style.display = \'\';
}

'; ?>

</script>