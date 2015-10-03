<?php /* Smarty version 2.6.9, created on 2015-09-27 18:24:31
         compiled from backend/tiny_mce_config.html */ ?>
<?php if (true): ?><script type="text/javascript" src="./tinymce/tiny_mce_gzip.js"></script>
<script type="text/javascript">
<?php echo '
tinyMCE_GZ.init({
	plugins : "toolbartoggle,safari,style,table,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,fullscreen,visualchars,xhtmlxtras",
	themes : \'advanced\','; ?>

	languages : "<?php echo $this->_tpl_vars['lang_iso2']; ?>
",<?php echo '	
	disk_cache : true,
	debug : false
});
'; ?>

</script>
<?php else: ?>
<script type="text/javascript" src="./tinymce/tiny_mce.js"></script>
<?php endif; ?>

<!-- Needs to be seperate script tags! -->
<script type="text/javascript"><!--
<?php echo '
initArray = {
'; ?>

	docs_language : "<?php echo $this->_tpl_vars['lang_iso2']; ?>
",
	language : "<?php echo $this->_tpl_vars['lang_iso2']; ?>
",
<?php echo '
	mode : "specific_textareas",
	editor_selector : "mceEditor",
	height: "350px",
	width : "100%",
	theme : "advanced",
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_path_location : "bottom",
	theme_advanced_resizing : true,
	dialog_type : "modal",
	theme_advanced_resize_horizontal : false,
	convert_urls : false,
	relative_urls : false,
	remove_script_host : false,
	force_p_newlines : false,
	force_br_newlines : false,
	convert_newlines_to_brs : false,
	remove_linebreaks : false,
	fix_list_elements : false,
	gecko_spellcheck : false,

	entities : "38,amp,60,lt,62,gt",
	button_tile_map : true,
	entity_encoding : "raw",
	
	cleanup : true,
	cleanup_on_startup : true,
	verify_html : true,
'; ?>

	content_css:"<?php echo @URL_ROOT; ?>
/tinymce/tiny_mce_css.php?css=<?php echo $this->_tpl_vars['url_current_theme_css']; ?>
,<?php echo @URL_CSS; ?>
/general.css",
<?php echo '
	plugins : "toolbartoggle,safari,style,table,advimage,advlink,inlinepopups,insertdatetime,media,searchreplace,contextmenu,paste,fullscreen,visualchars,xhtmlxtras",
	
	// Theme options
	theme_advanced_buttons1 : "fullscreen,|,code,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,bullist,numlist,|,outdent,indent,|,link,unlink,anchor,media,image,|,forecolor,backcolor,|,toolbartoggle",
	theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,cleanup,|,undo,redo,|,insertdate,inserttime,|,search,replace,|,sub,sup,charmap,|,cite,abbr,acronym,del,ins,|,blockquote,|,attribs,code,styleprops,|,help",
	theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,styleselect,formatselect,fontselect,fontsizeselect"
		
//	theme_advanced_buttons1 : "bold,italic,strikethrough,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,separator,link,unlink,image,wp_more,separator,spellchecker,separator,wp_help,wp_adv_start,wp_adv,separator,formatselect,underline,justifyfull,forecolor,separator,pastetext,pasteword,separator,removeformat,cleanup,separator,charmap,separator,undo,redo,wp_adv_end",
};

tinyMCE.init(initArray);
//-->
</script>
'; ?>