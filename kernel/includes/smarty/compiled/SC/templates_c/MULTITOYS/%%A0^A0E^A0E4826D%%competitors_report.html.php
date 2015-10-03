<?php /* Smarty version 2.6.9, created on 2015-10-01 10:52:59
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/competitors_report.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'set_query', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/competitors_report.html', 80, false),array('modifier', 'translate', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/competitors_report.html', 84, false),array('modifier', 'escape', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/competitors_report.html', 154, false),array('function', 'cycle', '/var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/backend/competitors_report.html', 181, false),)), $this); ?>
<?php echo '
    <style>
        .za_body h3 {
            padding-left: 5px;
            padding-top: 15px;
            color: tomato;
        }

        h3 small {
            font-size: small;
            color: #000033;
        }
        
        .to_right {
            text-align: right;
            color: darkorange;
        }

        label {
            vertical-align: top;
        }

        .red {
            color: red;
        }

        .yellow {
            color: yellow;
        }

        .green {
            color: green;
        }

        .blue {
            color: blue;
        }

        .choco {
            color: #9800A0;
        }

        .bold {
            font-weight: 700;
        }
        
        .border {
            border-left: 1px dotted grey;
        }

        .brand {
            color: gray;
            font-size: 11px;
            font-style: italic;
        }

        tr.odd:hover td,
        tr.even:hover td {
            background-color: #ECA;
        }
    </style>
'; ?>

                                                                <div class="za_body">
    <h3>Сравнение цен по конкурентам
        <small>(<?php echo $this->_tpl_vars['TotalFound']; ?>
)</small>
    </h3>
    <form name="MainForm" method="get" action="<?php echo ((is_array($_tmp='')) ? $this->_run_mod_handler('set_query', true, $_tmp) : smarty_modifier_set_query($_tmp)); ?>
">
        <input type="hidden" name="did" value="<?php echo $this->_tpl_vars['CurrentDivision']['id']; ?>
"/>
        <table border="0" cellspacing="1" cellpadding="3">
            <tr>
                <td colspan="10"><p><?php echo ((is_array($_tmp='competitors_search_params')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
</p></td>
            </tr>
            <tr>
                <td>
                    <label for="manufactured" style="color: #45AF10;">Производители</label>
                    <select name="manufactured" id="manufactured">
                        <option value='all'      <?php if ($_GET['manufactured'] == 'all'): ?> selected="selected"<?php endif; ?>>Все производители</option>
                        <option value='Ukraine' <?php if ($_GET['manufactured'] == 'Ukraine'): ?> selected="selected"<?php endif; ?>>только Украина</option>
                        <option value='China' <?php if ($_GET['manufactured'] == 'China'): ?> selected="selected"<?php endif; ?>>только Китай</option>
                    </select>
                </td>
                                                                                                                    <td>
                    <input type="checkbox" name="new_items_postup" value="1" id="new_items_postup"
                            <?php if ($_GET['new_items_postup']): ?> checked="checked"<?php endif; ?>>
                    <label for="new_items_postup" style="color: #C00DC1; font-style: italic">- только Новые
                                                                                             Поступления</label>
                </td>
                <td>
                    <input type="checkbox" name="bestsellers" value="1" id="bestsellers"
                            <?php if ($_GET['bestsellers']): ?> checked="checked"<?php endif; ?>>
                    <label for="bestsellers" style="color: coral; font-style: italic">- только Хиты продаж</label>
                </td>
                <td>
                    <input type="checkbox" name="new" value="1" id="new"
                            <?php if ($_GET['new']): ?> checked="checked"<?php endif; ?>>
                    <label for="new" style="color: #3A73BD; font-style: italic">- только Новинки</label>
                </td>
                <td>
                    <input type="checkbox" name="currency" value="1" id="currency"
                            <?php if ($_GET['currency']): ?> checked="checked"<?php endif; ?>>
                    <label for="currency" style="color: darkred; font-style: italic">- цены в $USD</label>
                </td>
                <td>
                    <label for="searchstring">Поиск товаров</label>
                    <input type="text" class="input_message" rel="Поиск товаров" value=""
                           name="searchstring" id="searchstring">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="competitor" style="color: #FF0000">Сравнить с: </label>
                    <select name="competitor" id="competitor">
                        <option value='all'      <?php if ($_GET['competitor'] == 'all'): ?> selected="selected"<?php endif; ?>>Все конкуренты</option>
                        <option value='kindermarket' <?php if ($_GET['competitor'] == 'kindermarket'): ?> selected="selected"<?php endif; ?>>
                            Киндер-Маркет
                        </option>
                        <option value='divoland' <?php if ($_GET['competitor'] == 'divoland'): ?> selected="selected"<?php endif; ?>>
                            Диволенд
                        </option>
                        <option value='dreamtoys'<?php if ($_GET['competitor'] == 'dreamtoys'): ?> selected="selected"<?php endif; ?>>
                            Веселка
                        </option>
                        <option value='mixtoys' <?php if ($_GET['competitor'] == 'mixtoys'): ?> selected="selected"<?php endif; ?>>
                            Микстойс
                        </option>
                        <option value='grandtoys' <?php if ($_GET['competitor'] == 'grandtoys'): ?> selected="selected"<?php endif; ?>>
                            ГрандТойс
                        </option>
                    </select>
                </td>
                <td>
                    <label for="brand" style="color: #C00DC1">Торговая марка: </label>
                    <select name="brand" id="brand">
                        <option value='all'>Все TM</option>
                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['Brands']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
                        <option value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Brands'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' <?php if ($_GET['brand'] == ((is_array($_tmp=$this->_tpl_vars['Brands'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['Brands'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
                    <?php endfor; endif; ?>
                    </select>
                </td>
                <td colspan="3">
                    <label for="category" style="color: darkorange">Категория: </label>
                    <select name="category" id="category">
                        <option value='all'>Все категории</option>
                    <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['Categories']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
                        <option value='<?php echo ((is_array($_tmp=$this->_tpl_vars['Categories'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
' <?php if ($_GET['category'] == ((is_array($_tmp=$this->_tpl_vars['Categories'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html'))): ?> selected="selected"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['Categories'][$this->_sections['i']['index']])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</option>
                    <?php endfor; endif; ?>
                    </select>
                </td>
                <td><input type="submit" name="search" value="<?php echo ((is_array($_tmp='btn_find')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
"></td>
            </tr>
        </table>
    </form>
    
    <?php if ($this->_tpl_vars['GridRows']): ?>
                        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="grid">
            <tr class="gridsheader">
                <?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/gridheader.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
            </tr>
            <?php unset($this->_sections['i']);
$this->_sections['i']['name'] = 'i';
$this->_sections['i']['loop'] = is_array($_loop=$this->_tpl_vars['GridRows']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
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
?>
                                <tr class="<?php echo smarty_function_cycle(array('values' => "odd,even"), $this);?>
">
                    <td class="to_right"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['num']; ?>
.</td>
                    <td>
                                                <?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['code_1c']; ?>

                                            </td>
                    <td class="blue"><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['product_code'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
                                        <td>
                        <img class="preview" width="64px" height="48px" src="<?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['img'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
" alt="Нет фото">
                    </td>
                    <td <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['max_diff'] > 0): ?>class="red"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['name_ru'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
                    <td class="brand"><?php echo ((is_array($_tmp=$this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['category'])) ? $this->_run_mod_handler('escape', true, $_tmp, 'html') : smarty_modifier_escape($_tmp, 'html')); ?>
</td>
                    <td class="to_right choco"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['purchase']; ?>
</td>
                    <td class="to_right choco"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['margin']; ?>
</td>
                    <td class="to_right bold <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['max_diff'] > 0): ?>red<?php else: ?>blue<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['Price']; ?>
</td>
                    <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['max_diff'] > 0): ?>red<?php else: ?>green<?php endif; ?>">
                        <strong><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['max_diff']; ?>
</strong>
                    </td>
                    <?php if ($_GET['competitor'] == 'divoland'): ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['divoland']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland']; ?>
</td>
                    <?php elseif ($_GET['competitor'] == 'dreamtoys'): ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['dreamtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys']; ?>
</td>
                    <?php elseif ($_GET['competitor'] == 'mixtoys'): ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['mixtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys']; ?>
</td>
                    <?php elseif ($_GET['competitor'] == 'grandtoys'): ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['grandtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys']; ?>
</td>
                    <?php elseif ($_GET['competitor'] == 'kindermarket'): ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['kindermarket']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket']; ?>
</td>
                    <?php else: ?>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['divoland']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_divoland']; ?>
</td>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['dreamtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_dreamtoys']; ?>
</td>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['mixtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_mixtoys']; ?>
</td>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['grandtoys']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_grandtoys']; ?>
</td>
                        <td class="to_right border <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['kindermarket']; ?>
</td>
                        <td class="to_right <?php if ($this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket'] > 0): ?>red<?php else: ?>green<?php endif; ?>"><?php echo $this->_tpl_vars['GridRows'][$this->_sections['i']['index']]['diff_kindermarket']; ?>
</td>
                    <?php endif; ?>
                </tr>
            <?php endfor; endif; ?>
            <tr class="gridsfooter">
                <td colspan="11"><?php echo $this->_tpl_vars['TotalFound']; ?>
 &nbsp;<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/lister.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p>&lt;<?php echo ((is_array($_tmp='lbl_not_found')) ? $this->_run_mod_handler('translate', true, $_tmp) : smarty_modifier_translate($_tmp)); ?>
&gt;</p>
    <?php endif; ?>
</div>