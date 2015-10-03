<?php /* Smarty version 2.6.9, created on 2015-10-02 13:54:34
         compiled from backend/modules_yandex.tpl.html */ ?>
<h1>Экспорт товаров в Яндекс.Маркет</h1>
<?php if ($this->_tpl_vars['yandex_errormsg'] != NULL): ?>
<div id="error-block" class="error_block">
<?php echo $this->_tpl_vars['yandex_errormsg']; ?>

</div>
<?php endif;  if ($this->_tpl_vars['yandex_export_successful'] == 1): ?>
<div id="message-block" class="success_block">Товары успешно экспортированы в XML-файл для Яндекс.Маркет!</div>
<?php endif;  if ($this->_tpl_vars['yandex_file']): ?>
<div style="background-color: #fafae7;padding: 20px;margin: 10px 0;">
Файл для Яндекс.Маркета (<?php echo $this->_tpl_vars['yandex_file']['size']; ?>
 КБ; обновлен <?php echo $this->_tpl_vars['yandex_file']['mtime']; ?>
):<br>
<ol style="list-style-type:none;padding-left: 10px;">
<li>
Прямой доступ (URL): <b><a target="_blank" href="<?php echo @BASE_WA_URL;  if (! @MOD_REWRITE_SUPPORT || true):  if (! @CONF_ON_WEBASYST): ?>published/<?php endif; ?>SC/html/scripts/<?php endif; ?>get_file.php?getFileParam=R2V0WWFuZGV4"><?php echo @BASE_WA_URL;  if (! @MOD_REWRITE_SUPPORT || true):  if (! @CONF_ON_WEBASYST): ?>published/<?php endif; ?>SC/html/scripts/<?php endif; ?>get_file.php?getFileParam=R2V0WWFuZGV4</a></b>
</li>
<?php if (false && $this->_tpl_vars['base_url'] && base_url != @BASE_WA_URL): ?>
<li>
Прямой доступ (с учетом указанного адреса главной страницы)(URL): <b><a target="_blank" href="<?php echo $this->_tpl_vars['base_url'];  if (! @CONF_ON_WEBASYST): ?>published/SC/html/scripts/<?php endif; ?>get_file.php?getFileParam=R2V0WWFuZGV4"><?php echo $this->_tpl_vars['base_url'];  if (! @CONF_ON_WEBASYST): ?>published/SC/html/scripts/<?php endif; ?>get_file.php?getFileParam=R2V0WWFuZGV4</a></b>
</li>
<?php endif; ?>
<li>
<a target="_blank" href='<?php echo @BASE_WA_URL;  if (! @MOD_REWRITE_SUPPORT || true):  if (! @CONF_ON_WEBASYST): ?>published/<?php endif; ?>SC/html/scripts/<?php endif; ?>get_file.php?getFileParam=R2V0WWFuZGV4&amp;download=1'>Скачать файл</a> 
</li>
</ol>
</div>
<?php endif;  if ($this->_tpl_vars['yandex_export_successful'] != 1):  if ($this->_tpl_vars['yandex_file']): ?>
<h2>Обновить файл для Яндекс.Маркет</h2>
<?php endif; ?>
<form action="" method=post name="form_export">
	<input type="hidden" name="yandex_export" value="" />
	<ol style="1">
	<li>
	<p><b class="header2">Адрес главной страницы интернет-магазина:</b>
	<br/>
	<input name="base_url" type="text" value="<?php if ($this->_tpl_vars['base_url']):  echo $this->_tpl_vars['base_url'];  else:  echo @WIDGET_SHOP_URL;  endif; ?>" size="40">
	</li>
	<li>
		<b class="header2">Выберите категории и товары</b>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "backend/product_tree.tpl.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	</li>
	<li>
		<b class="header2">Выберите параметры</b>

	<p>Цены на товары в магазине, экспортируемые в XML-файл для Яндекс.Маркет, указываются в рублях (RUR).
	<br>
	Пожалуйста, укажите курс рубля в Вашем магазине - введите сколько условных единиц (у.е.) в магазине составляют 1 рубль.
	<br>
	Например, если Вы введете 5, то это будет означать, что 1 рубль = 5 у.е. магазина.
	<br>
	Рекомендуется вводить тот курс, который Вы указали для валюты "Рубли" (если таковая определена) в разделе "Настройки->Валюты".
	</p>

	<p>1 рубль = <input type=text name=yandex_rur_rate value=1> у.е.</p>

	<p>
	Какие описания товаров экспортировать в файл?<br>
	ВАЖНО: Описание не должно содержать HTML-тэгов и по размеру не может быть более 255 символов<br>(если 
описание выходит за пределы допустимого значения,то текст обрезается и в конце проставляется многоточие)<br>
	</p>
	<select name=yandex_export_description>
		<option value=0>Не экспорировать описания</option>
		<option value=1>Полное описание</option>
		<option value=2 selected>Краткое описание</option>
	</select>

	<?php if (@CONF_CHECKSTOCK == 1): ?>
	<p>
	<input type="checkbox" name="yandex_dont_export_negative_stock" value="1" id="id_yenstock" class="checknomarging" checked="checked" /><label for="id_yenstock">  Не экспортировать товары с нулевым остатком на складе</label><br>
	</p>
	<?php endif; ?>
	<p>Экспорт названий товаров:
	<br />
	<select name="yandex_export_product_name">
		<option value="only_name">только название товара</option>
		<option value="path_and_name">путь к категории товара + название товара</option>
	</select>
	</p>
	<p>&lt;sales_notes&gt;:
	<br />
		<input type="text" name="yandex_export_sales_notes" value="">
	</li>
	</ol>
	<p>
	<input type=button onclick="document.form_export.elements['yandex_export'].value='yandex_export';document.form_export.submit(); return false;" value="Экспортировать товары в XML-файл">
	<input type=hidden name=dpt value=modules>
	<input type=hidden name=sub value=yandex>
	<input type=hidden name=_export value="" />
	</p>
</form>
<?php endif; ?>