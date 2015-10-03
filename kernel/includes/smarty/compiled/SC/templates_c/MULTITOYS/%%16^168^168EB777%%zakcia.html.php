<?php /* Smarty version 2.6.9, created on 2015-09-21 23:18:47
         compiled from /var/www/multitoy/data/www/multitoys.com.ua/published/SC/html/scripts/templates/frontend/zakcia.html */ ?>
<script defer src="<?php echo @URL_JS; ?>
/jquery.countdown-1437576318769.js" type="text/javascript"></script>
<a href="/category/akcija/">
<div class="zakcia_body" data-time="<?php echo $this->_tpl_vars['seconds']; ?>
">
    <div class="left">
		<div>
			<p id = "end">до конца акции осталось...</p>
		</div>
		<div id="z_counter"></div>
		<div class="desc">
			<div>Дней</div>
			<div>Часов</div>
			<div>Минут</div>
			<div>Секунд</div>
		</div >
	</div>
	<?php echo $this->_tpl_vars['za_body']; ?>

</div>
</a>
<br />