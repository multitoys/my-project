<?php /* Smarty version 2.6.9, created on 2015-09-22 08:33:36
         compiled from backend/google_api/gmaps_ext_popup.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'translate', 'backend/google_api/gmaps_ext_popup.html', 39, false),)), $this); ?>
<script type="text/javascript" src="<?php echo @URL_JS; ?>
/JsHttpRequest.js"></script>
<script language="JavaScript" type="text/javascript">
<?php echo '
window.alert = function() { return false; };
'; ?>

</script>

<?php if (@CONF_GOOGLE_MAPS_API_KEY != ''): ?>
<script type="text/javascript" language="JavaScript" src="http://maps.google.com/maps?file=api&amp;v=2.x&amp;key=<?php echo @CONF_GOOGLE_MAPS_API_KEY; ?>
"></script>
<?php endif; ?>
<script type="text/javascript" language="JavaScript">
<!--
<?php echo '

var map_win;
var current_show_addr;
var rd_win;
var render_from_ca = false;

function showMapWindow(addr)
{
    current_show_addr = addr;
    render_from_ca = false;
    // create the window on the first click and reuse on subsequent clicks
    if(!map_win){
        map_win = new Ext.Window({
            el: \'';  echo $this->_tpl_vars['map_win_name'];  echo '\',
            layout: \'fit\',
            width: 707,
            height: 500,
            closeAction: \'hide\',
            plain: true,
            autoScroll: false,
            resizable: false,
            modal: true,
            
            tbar: [
             {
                text: \'';  echo ((is_array($_tmp='btn_change_addr')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
               ,handler: function() { 
                       Ext.Msg.show({
                               title: \'';  echo ((is_array($_tmp='str_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\',
                               value: current_show_addr,
                               width: 300,
                               buttons: {\'ok\': \'';  echo ((is_array($_tmp='btn_ok')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\', \'cancel\': \'';  echo ((is_array($_tmp='btn_cancel')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'},
                               multiline: true,
                               icon: Ext.MessageBox.QUESTION,
                               fn: function(btn, text) {
                                current_show_addr = text;
                                render_from_ca = true;
                                if(btn == \'ok\') renderMapForAddress(text.replace("\\n"," "));
                               }
                        });       
                 }
               ,disabled: ('; ?>
'<?php echo @CONF_GOOGLE_MAPS_API_KEY; ?>
'<?php echo ' == \'\' || G_INCOMPAT ? true : false)
             }
            ,{
                text: \'';  echo ((is_array($_tmp='btn_make_route')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
               ,handler: function() { showRDWindow(); }
               ,disabled: ('; ?>
'<?php echo @CONF_GOOGLE_MAPS_API_KEY; ?>
'<?php echo ' == \'\' || G_INCOMPAT ? true : false)
             }
            ,{
                text: \'';  echo ((is_array($_tmp='btn_print')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
               ,handler: function() {
                    var map_center = map.getCenter().toUrlValue();
                    window.open(\'http://maps.google.com/maps?ll=\'+map_center+\'&z=\'+map.getZoom()+\'&key=';  echo @CONF_GOOGLE_MAPS_API_KEY;  echo '&pw=2\');
               }
               ,disabled: ('; ?>
'<?php echo @CONF_GOOGLE_MAPS_API_KEY; ?>
'<?php echo ' == \'\' || G_INCOMPAT ? true : false)
             }
            ,{
                text: \'\'
               ,minWidth: 50
               ,disabled: true
             }
            ,{
                text: \'';  echo ((is_array($_tmp='btn_close')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\',
                handler: function(){
                    map_win.hide();
                }
             }
            ]
        });
    }
    map_win.show(map_win);
    
    if(\'';  echo @CONF_GOOGLE_MAPS_API_KEY;  echo '\' != \'\')
    {
        if(map == null)
        {
            gmap_initialize();
        };
        renderMapForAddress(addr);
        return;
    };    
    
};

function showRDWindow()
{
    if(!rd_win)
    {
        rd_win = new Ext.Window({
            el: \'';  echo $this->_tpl_vars['map_win_name'];  echo '_rd\',
            layout: \'fit\',
            width: 307,
            height: 220,
            closeAction: \'hide\',
            plain: true,
            autoScroll: false,
            resizable: false,
            modal: true,
            buttonAlign: \'center\',
            buttons: [
                {
                    text: \'';  echo ((is_array($_tmp='btn_make_route_short')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
                   ,handler: function() { renderDirection(); }
                }
               ,{
                    text: \'';  echo ((is_array($_tmp='btn_cancel')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
                   ,handler: function() { rd_win.hide(); }
                }
            ]
        });
    };
    
    document.getElementById(\'';  echo $this->_tpl_vars['map_win_name'];  echo '_rd_to\').innerHTML = current_show_addr;
    rd_win.show(rd_win);
};

var map = null;
var geocoder = null;

function gmap_initialize()
{
  if(G_INCOMPAT)
  {
    var el = document.getElementById(\'';  echo $this->_tpl_vars['map_canvas_name'];  echo '\');
    var _html = \'<center style="padding: 150px 20px 0px 20px;">\';
    _html += \'';  echo ((is_array($_tmp='wrn_invalid_google_maps_api_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\';
    _html += \'<br\\/><br\\/><br\\/>\';
    _html += \'';  echo ((is_array($_tmp='lbl_enter_gmapi_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo ': <input type="text" id="gmapi_key_val" value="" size="40" \\/>\';
    _html += \'<button id="gmapi_key_sbut" type="button" onClick="checkGMAPIKey();">';  echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '<\\/button>\';
    _html += \'<\\/center>\';
    el.innerHTML = _html;
    return false;
  };
  if (GBrowserIsCompatible())
  {
    map = new GMap2(document.getElementById("';  echo $this->_tpl_vars['map_canvas_name'];  echo '"), {size: new GSize(700,500)});
    map.enableScrollWheelZoom();
    var mapControl = new GMapTypeControl();
    map.addControl(mapControl);
    map.addControl(new GLargeMapControl());
    geocoder = new GClientGeocoder();
  }
};

function renderMapForAddress(address)
{
  if (geocoder)
  {
    geocoder.getLatLng(
      address,
      function(point) {
        if (!point) {
            Ext.Msg.show({
               title: \'';  echo ((is_array($_tmp='str_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\',
               msg: \'';  echo ((is_array($_tmp='lbl_not_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\',
               value: address,
               width: 300,
               buttons: {\'ok\': \'';  echo ((is_array($_tmp='btn_search_again')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\', \'cancel\': \'';  echo ((is_array($_tmp='btn_cancel')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'},
               multiline: true,
               icon: Ext.MessageBox.WARNING,
               fn: function(btn, text) {
                current_show_addr = text;
                if(btn == \'ok\') renderMapForAddress(text.replace("\\n"," "));
                else if(!render_from_ca) map_win.hide();
               }
            });       
      } else {
          map.setCenter(point, 13);
          var marker = new GMarker(point);
          map.addOverlay(marker);
          marker.openInfoWindowHtml(address);
        }
      }
    );
  }
};

var directions = null;
var can_make_route_from = null;
var can_make_route_to = null;

function renderDirection()
{
    if(directions == null)
        directions = new GDirections(map);
    else
        directions.clear();
    
    var addr_from = document.getElementById(\'';  echo $this->_tpl_vars['map_win_name'];  echo '_rd_from\').value.replace(/\\s+/g,\' \');
    var addr_to = document.getElementById(\'';  echo $this->_tpl_vars['map_win_name'];  echo '_rd_to\').value.replace(/\\s+/g,\' \');

    GEvent.addListener(directions, "load", onGDirectionsLoad);
    GEvent.addListener(directions, "error", handleErrors);
    //directions.load(addr_from+\' to \'+addr_to);
    directions.load(\'from: \'+addr_from+\' to: \'+addr_to);
};

function handleErrors()
{
   switch(directions.getStatus().code)
   {
        case G_GEO_UNKNOWN_ADDRESS:
                emsg = \'';  echo ((is_array($_tmp='gerr_geo_unknown_address')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
        case G_GEO_SERVER_ERROR:
                emsg = \'';  echo ((is_array($_tmp='gerr_geo_server_error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
        case G_GEO_MISSING_QUERY:
                emsg = \'';  echo ((is_array($_tmp='gerr_geo_missing_query')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
        case G_GEO_BAD_KEY:
                emsg = \'';  echo ((is_array($_tmp='gerr_geo_bad_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
        case G_GEO_BAD_REQUEST:
                emsg = \'';  echo ((is_array($_tmp='gerr_geo_bad_request')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
        default: emsg = \'';  echo ((is_array($_tmp='gerr_unknown')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'; break;
   };

   Ext.Msg.show({
        buttons: Ext.MessageBox.OK
       ,icon: Ext.MessageBox.ERROR
       ,title: \'';  echo ((is_array($_tmp='lbl_error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
       ,msg: emsg
     });
   
};

function onGDirectionsLoad()
{
    if(directions.getStatus().code == G_GEO_SUCCESS)
    {
        rd_win.hide();
        
        if(\'';  echo @CONF_WAREHOUSE_ADDRESS;  echo '\' == \'\' && !addr_cfg_saved)
        {
            saveAddrCfg();
        };
    };
}; 

var addr_cfg_saved = false;
function saveAddrCfg()
{
    var addr_cfg = document.getElementById(\'';  echo $this->_tpl_vars['map_win_name'];  echo '_rd_from\').value;

    var req = new JsHttpRequest();
    
    req.onreadystatechange = function()
    {
        if (req.readyState != 4) return;
        if(req.responseText) alert(req.responseText);
        
        addr_cfg_saved = true;
    };
    
    try
    {
        req.open(null, set_query(\'&caller=1&initscript=ajaxservice&ukey=configuration\'), true);
        req.send({\'action\': \'ajax_set_setting\', \'setting_name\': \'CONF_WAREHOUSE_ADDRESS\', \'setting_value\': addr_cfg});
    }
    catch ( e )
    {
      catchResult(e);
    }
    finally { ;}
};

function checkGMAPIKey()
{
var field_el = document.getElementById(\'gmapi_key_val\');
    
    if(field_el.value == \'\')
    {
       Ext.Msg.show({
            buttons: Ext.MessageBox.OK
           ,icon: Ext.MessageBox.ERROR
           ,title: \'';  echo ((is_array($_tmp='lbl_error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
           ,msg: \'';  echo ((is_array($_tmp='wrn_invalid_google_maps_api_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
         });
        field_el.focus();
        return;
    };
    
    if(!document.getElementById(\'gmapi_check_iframe\'))
    {
        var gmf = document.createElement(\'IFRAME\');
        gmf.id = \'gmapi_check_iframe\';
        gmf.frameborder = 0;
        gmf.height = 0;
        gmf.width = 0;
        gmf.marginheight = 0;
        gmf.marginwidth = 0;
        gmf.scrolling = \'no\';
        gmf.style.width = \'0px\';
        gmf.style.height = \'0px\';
        gmf.style.border = \'0px\';
        gmf.style.visibility = \'hidden\';
        gmf.style.position = \'absolute\';
        document.body.appendChild(gmf);
    }
    else
    {
        var gmf = document.getElementById(\'gmapi_check_iframe\');
    };
    
    Ext.Msg.wait(\'';  echo ((is_array($_tmp='lbl_please_wait')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\', \'';  echo ((is_array($_tmp='lbl_checking_api_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\');
    
    gmf.src = \'index.php?ukey=gmapi_key_checker&gmapi_key=\'+field_el.value;
};

function handleGMAPIKeyChecker(is_correct)
{
    var field_el = document.getElementById(\'gmapi_key_val\');
    
    if(is_correct)
    {
        saveGMAPIKey(field_el.value);
    }
    else
    {
       Ext.Msg.hide();
       Ext.Msg.show({
            buttons: Ext.MessageBox.OK
           ,icon: Ext.MessageBox.ERROR
           ,title: \'';  echo ((is_array($_tmp='lbl_error')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
           ,msg: \'';  echo ((is_array($_tmp='wrn_invalid_google_maps_api_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  echo '\'
         });
        field_el.focus();
    };
};

function saveGMAPIKey(key_value)
{
    var req = new JsHttpRequest();
    
    req.onreadystatechange = function()
    {
        if (req.readyState != 4) return;
        if(req.responseText) alert(req.responseText);
        
        window.location.reload();
    };
    
    try
    {
        req.open(null, set_query(\'&caller=1&initscript=ajaxservice&ukey=configuration\'), true);
        req.send({\'action\': \'ajax_set_setting\', \'setting_name\': \'CONF_GOOGLE_MAPS_API_KEY\', \'setting_value\': key_value});
    }
    catch ( e )
    {
      catchResult(e);
    }
    finally { ;}
};

'; ?>

//-->
</script>

<div id="<?php echo $this->_tpl_vars['map_win_name']; ?>
" class="x-hidden">
    <div class="x-window-header"><?php echo ((is_array($_tmp='lbl_address_lookup')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
    <div class="x-window-body" id="<?php echo $this->_tpl_vars['map_canvas_name']; ?>
">
    <?php if (@CONF_GOOGLE_MAPS_API_KEY == ''): ?>
    <center style="padding: 150px 20px 0px 20px;">
    <?php echo ((is_array($_tmp='wrn_no_google_maps_api_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>

    <br/><br/><br/>
    <?php echo ((is_array($_tmp='lbl_enter_gmapi_key')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
: <input type="text" id="gmapi_key_val" value="" size="40" />
    <button id="gmapi_key_sbut" type="button" onClick="checkGMAPIKey();"><?php echo ((is_array($_tmp='btn_save')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</button>
    </center>
    <?php endif; ?>
    </div>
</div>

<div id="<?php echo $this->_tpl_vars['map_win_name']; ?>
_rd" class="x-hidden">
    <div class="x-window-header"><?php echo ((is_array($_tmp='btn_make_route')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</div>
    <div class="x-window-body" id="<?php echo $this->_tpl_vars['map_canvas_name']; ?>
_rd">
        <table>
            <tr>
                <td valign="top" align="right"><?php echo ((is_array($_tmp='lbl_route_from')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
                <td>
                    <textarea id="<?php echo $this->_tpl_vars['map_win_name']; ?>
_rd_from" cols="35" rows="3" style="border: solid 1px black;"><?php if (@CONF_WAREHOUSE_ADDRESS != ''):  echo @CONF_WAREHOUSE_ADDRESS;  else:  echo ((is_array($_tmp='msg_input_addr_from')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp));  endif; ?></textarea>
                </td>
            </tr>
            <tr>
                <td valign="top" align="right"><?php echo ((is_array($_tmp='lbl_route_to')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</td>
                <td>
                    <textarea id="<?php echo $this->_tpl_vars['map_win_name']; ?>
_rd_to" cols="35" rows="3" style="border: solid 1px black;"></textarea>
                </td>
            </tr>
        </table>
    </div>
</div>