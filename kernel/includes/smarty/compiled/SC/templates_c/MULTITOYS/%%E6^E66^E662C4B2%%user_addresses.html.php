<?php /* Smarty version 2.6.9, created on 2015-09-23 10:06:12
         compiled from backend/user_addresses.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/user_addresses.html', 25, false),array('modifier', 'cat', 'backend/user_addresses.html', 35, false),array('modifier', 'escape', 'backend/user_addresses.html', 206, false),)), $this); ?>
<script type="text/javascript" src='../../../common/html/res/ext/pr-prototype.js'></script>
<script type="text/javascript" src='../../../common/html/res/ext/pr-adapter.js'></script>
<script type="text/javascript" src='../../../common/html/res/ext/pr-effects.js'></script>
<script type="text/javascript" src='../../../common/html/res/ext/ext-all.js'></script>
<script type="text/javascript" src="../../../common/html/cssbased/domready.js"></script>
<link rel='stylesheet' type='text/css' href='../../../common/html/res/ext/resources/css/sc-my-ext-all.css'>
<link rel='stylesheet' type='text/css' href='../../../common/html/res/ext/resources/css/xtheme-slate.css'>
<link rel='stylesheet' type='text/css' href='../../../common/html/res/ext/resources/css/menu.css'>
<link rel='stylesheet' type='text/css' href='../../../common/html/res/ext/resources/css/layout.css'>
<script type="text/javascript">Ext.BLANK_IMAGE_URL = '../../../common/html/res/ext/resources/images/default/s.gif'</script>

<script type="text/javascript" src="<?php echo @URL_JS; ?>
/JsHttpRequest.js"></script>

<br />

<table cellpadding="4" border="0" width="100%">
    <colgroup>
        <col width="50%" />
        <col width="50%" />
    </colgroup>
<?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['addresses']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['i']['show'] = true;
$this->_sections['i']['max'] = $this->_sections['i']['loop'];
$this->_sections['i']['step'] = 1;
$this->_sections['i']['start'] = $this->_sections['i']['step'] > 0 ? 0 : $this->_sections['i']['loop']-1;
if ($this->_sections['i']['show']) {
    $this->_sections['i']['total'] = $this->_sections['i']['loop'];
    if ($this->_sections['i']['total'] == 0)
        $this->_sections['i']['show'] = false;
} else
    $this->_sections['i']['total'] = 0;
if ($this->_sections['i']['show']):

            for ($this->_sections['i']['index'] = $this->_sections['i']['start'], $this->_sections['i']['iteration'] = 1;
                 $this->_sections['i']['iteration'] <= $this->_sections['i']['total'];
                 $this->_sections['i']['index'] += $this->_sections['i']['step'], $this->_sections['i']['iteration']++):
$this->_sections['i']['rownum'] = $this->_sections['i']['iteration'];
$this->_sections['i']['index_prev'] = $this->_sections['i']['index'] - $this->_sections['i']['step'];
$this->_sections['i']['index_next'] = $this->_sections['i']['index'] + $this->_sections['i']['step'];
$this->_sections['i']['first']      = ($this->_sections['i']['iteration'] == 1);
$this->_sections['i']['last']       = ($this->_sections['i']['iteration'] == $this->_sections['i']['total']);
 $this->assign('addr_id', $this->_tpl_vars['addresses'][$this->_sections['i']['index']]['addressID']); ?>
    <tr style="height: 10px;"></tr>
    <?php if ($this->_sections['i']['index'] == 0): ?>
        <tr><td><b><?php echo ((is_array($_tmp='usr_custinfo_default_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</b></td></tr>
    <?php elseif ($this->_sections['i']['index'] == 1): ?>
        <tr><td><b><?php echo ((is_array($_tmp='usr_custinfo_other_addresses')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
:</b></td></tr>
    <?php elseif ($this->_sections['i']['index'] > 1): ?>
    	<tr style="height: 1px; background-color: #F5F0BB;"><td colspan="2"></td></tr>
    <?php endif; ?>
    <tr style="height: 10px;"></tr>
	<tr>
		<td>
			<span id="addr_<?php echo $this->_tpl_vars['addresses'][$this->_sections['i']['index']]['addressID']; ?>
_str"><?php echo $this->_tpl_vars['addresses'][$this->_sections['i']['index']]['addressStr']; ?>
</span>
            <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/userinfo_editor/address_form.html", 'smarty_include_vars' => array('addr_info' => $this->_tpl_vars['addresses'][$this->_sections['i']['index']],'els_prefix' => ((is_array($_tmp=((is_array($_tmp='addr[')) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['addr_id']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['addr_id'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ']') : smarty_modifier_cat($_tmp, ']')),'addr_id' => $this->_tpl_vars['addr_id'])));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		</td>
        <td align="left" valign="top">
            <table border="0" cellpadding="2" cellspacing="1">
                <tr>
                    <td width="35"><a id="elink_<?php echo $this->_tpl_vars['addr_id']; ?>
" href="javascript: void(0);" onClick="showEditForm(<?php echo $this->_tpl_vars['addresses'][$this->_sections['i']['index']]['addressID']; ?>
);" class="dashed_link"><?php echo ((is_array($_tmp='btn_edit')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
                    <?php if ($this->_tpl_vars['addresses_count'] > 1): ?>
                    <td width="50" style="padding-left: 10px;"><a href="javascript: void(0);" onClick="delAddress(<?php echo $this->_tpl_vars['addr_id']; ?>
);"><?php echo ((is_array($_tmp='btn_delete')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>
                    <?php endif; ?>
                    <?php if ($this->_tpl_vars['addresses'][$this->_sections['i']['index']]['addressID'] != $this->_tpl_vars['defaultAddressID']): ?>
                    <td width="55" style="padding-left: 10px;"><a href="javascript: void(0);" onClick="setDefaultAddr(<?php echo $this->_tpl_vars['addr_id']; ?>
);"><?php echo ((is_array($_tmp='str_default')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a></td>  
                    <?php endif; ?>
                    <td width="90" style="padding-left: 10px;"><a href="javascript: void(0);" id="al_<?php echo $this->_tpl_vars['addr_id']; ?>
"><span style="white-space: nowrap;"><?php echo ((is_array($_tmp='lbl_lookup')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</span></a></td>
                </tr>
            </table>
        </td>
	</tr>
<?php endfor; endif; ?>
</table>

<form name="aux_form" method="post">
<input type="hidden" name="action" value="">
<input type="hidden" name="addr_id" value="">
</form>

<br />
<span id="addr_0_str"></span>
<a id="elink_0" href="javascript: void(0);" onClick="showEditForm(0);" class="dashed_link"><?php echo ((is_array($_tmp='pgn_add_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</a>

<table cellpadding="4" border="0" width="100%">
    <colgroup>
        <col width="40%" />
        <col width="60%" />
    </colgroup>
    <tr><td>
    <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/userinfo_editor/address_form.html", 'smarty_include_vars' => array('els_prefix' => 'addr[0]','addr_id' => 0)));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
    </td><td></td></tr>
</table>

<script type="text/javascript" language="JavaScript">
<?php echo '

function showEditForm(addr_id)
{
    document.getElementById(\'addr_\'+addr_id+\'_str\').style.display = \'none\';
    document.getElementById(\'addr_\'+addr_id+\'_frm\').style.display = \'\';
    document.getElementById(\'elink_\'+addr_id).style.display = \'none\';
    if(document.getElementById(\'al_\'+addr_id))
        document.getElementById(\'al_\'+addr_id).style.display = \'none\';
};

function hideEditForm(addr_id)
{
    document.getElementById(\'addr_\'+addr_id+\'_str\').style.display = \'\';
    document.getElementById(\'addr_\'+addr_id+\'_frm\').style.display = \'none\';
    document.getElementById(\'elink_\'+addr_id).style.display = \'\';
    if(document.getElementById(\'al_\'+addr_id))
        document.getElementById(\'al_\'+addr_id).style.display = \'\';
};

function changeStates(country_el)
{
    var country_id  = country_el.value;
    var addr_id = country_el.name.replace(\'addr[\',\'\').replace(\'][countryID]\',\'\');
    var states_el = document.forms[\'AddrForm_\'+addr_id].elements[\'addr[\'+addr_id+\'][zoneID]\'];
    
    var req = new JsHttpRequest();
    
    req.onreadystatechange = function()
    {
        if (req.readyState != 4) return;
        if(req.responseText) alert(req.responseText);
        
        var states = req.responseJS.states;

        if(states.length > 0)
        {
            if(!states_el)
            {
                states_el = document.forms[\'AddrForm_\'+addr_id].elements[\'addr[\'+addr_id+\'][state]\'];
                var pn = states_el.parentNode;
                pn.removeChild(states_el);
                
                var dd = document.createElement(\'SELECT\');
                dd.name =\'addr[\'+addr_id+\'][zoneID]\';
                dd.className = \'txt_or_sel\';
                
                pn.appendChild(dd);
                states_el = document.forms[\'AddrForm_\'+addr_id].elements[\'addr[\'+addr_id+\'][zoneID]\'];
            }
            else
            {
                while(states_el.options.length > 0)
                {
                    states_el.remove(0);
                };
            };
        }
        else
        {
            if(states_el)
            {
                var pn = states_el.parentNode;
                pn.removeChild(states_el);
                
                var inp = document.createElement(\'INPUT\');
                inp.type = \'text\';
                inp.className = \'txt_or_sel\';
                inp.name = \'addr[\'+addr_id+\'][state]\';
                
                pn.appendChild(inp);
                states_el = document.forms[\'AddrForm_\'+addr_id].elements[\'addr[\'+addr_id+\'][state]\'];
            }
        };

        
        for(i=0; i<states.length; i++)
        {
            var opt = new Option();
            opt.value = states[i].zoneID;
            opt.text = states[i].zone_name;
            try
            {
                states_el.add(opt,null); // standards compliant
            }
            catch(ex)
            {
                states_el.add(opt); // IE only
            };        
        };
    };

    try
    {
        req.open(null, set_query(\'&caller=1&initscript=ajaxservice\'), true);
        req.send({\'action\': \'ajax_get_states\', \'country_id\': country_id});
    }
    catch ( e )
    {
      catchResult(e);
    }
    finally { ;}
};

function delAddress(addr_id)
{
    if(!confirm(\'';  echo ((is_array($_tmp='qst_delete_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'))
    {
        return false;
    };
    
    var frm = document.forms[\'aux_form\'];
    frm.elements[\'action\'].value = \'del_address\';
    frm.elements[\'addr_id\'].value = addr_id;
    frm.submit();
};

function setDefaultAddr(addr_id)
{
    var frm = document.forms[\'aux_form\'];
    frm.elements[\'action\'].value = \'set_default_address\';
    frm.elements[\'addr_id\'].value = addr_id;
    frm.submit();
};

'; ?>


Ext.onReady(function(){
    <?php $_from = $this->_tpl_vars['addresses']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['addr']):
?>
    var btn = Ext.get('al_'+<?php echo $this->_tpl_vars['addr']['addressID']; ?>
);
    btn.on('click', function(){
        var addr = '<?php echo ((is_array($_tmp=$this->_tpl_vars['addr']['country'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['addr']['city'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
 <?php echo ((is_array($_tmp=$this->_tpl_vars['addr']['address_js'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
';
        addr = addr.replace("\n", "");
        showMapWindow(addr);
    });
    <?php endforeach; endif; unset($_from); ?>
});
</script>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/google_api/gmaps_ext_popup.html", 'smarty_include_vars' => array('map_win_name' => 'addr_win','map_canvas_name' => 'map_canvas')));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>