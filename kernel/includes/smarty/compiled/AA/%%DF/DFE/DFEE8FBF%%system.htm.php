<?php /* Smarty version 2.6.9, created on 2015-10-02 16:33:32
         compiled from system.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'wbs_initLayout', 'system.htm', 4, false),array('function', 'wbs_errorBlock', 'system.htm', 9, false),array('function', 'html_options', 'system.htm', 16, false),array('function', 'wbs_label', 'system.htm', 26, false),array('function', 'wbs_checkboxStatus', 'system.htm', 30, false),array('function', 'wbs_buttonSetButton', 'system.htm', 48, false),array('block', 'wbs_pageLayout', 'system.htm', 8, false),array('block', 'wbs_buttonSet', 'system.htm', 47, false),array('modifier', 'cat', 'system.htm', 14, false),array('modifier', 'htmlsafe', 'system.htm', 40, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
	<head>
		<?php echo smarty_function_wbs_initLayout(array(), $this);?>


	</head>
	<body onLoad="autoFocusFormControl( '<?php echo $this->_tpl_vars['invalidField']; ?>
', 'folderData' )">
		<?php $this->_tag_stack[] = array('wbs_pageLayout', array('toolbar' => "aa_toolbar.htm")); smarty_block_wbs_pageLayout($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>
			<?php echo smarty_function_wbs_errorBlock(array(), $this);?>


			<?php if (! $this->_tpl_vars['fatalError']): ?>

				<dl class="SimpleList">
					<dt><?php echo ((is_array($_tmp=$this->_tpl_vars['kernelStrings']['sys_dataformat_label'])) ? $this->_run_mod_handler('cat', true, $_tmp, ":&nbsp;") : smarty_modifier_cat($_tmp, ":&nbsp;")); ?>

						<select name="dateFormat" class="FormControl">
							<?php echo smarty_function_html_options(array('values' => $this->_tpl_vars['dateFormat_ids'],'selected' => $this->_tpl_vars['dateFormat'],'output' => $this->_tpl_vars['dateFormat_names']), $this);?>

						</select>
					</dt>
				</dl>
				<dl class="SimpleList">
					<dt><?php echo $this->_tpl_vars['kernelStrings']['sys_expinterval_text']; ?>
</dt>
					<dd>
						<table class="FormLayout">
							<tr>
								<?php if ($this->_tpl_vars['defPeriod']): ?>
									<td><?php echo smarty_function_wbs_label(array('for' => 'type0','text' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['kernelStrings']['sei_default_label'])) ? $this->_run_mod_handler('cat', true, $_tmp, " (") : smarty_modifier_cat($_tmp, " (")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['defPeriod']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['defPeriod'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ' ') : smarty_modifier_cat($_tmp, ' ')))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['kernelStrings']['sei_min_label']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['kernelStrings']['sei_min_label'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ")") : smarty_modifier_cat($_tmp, ")")),'skipColon' => true), $this);?>
</td>
								<?php else: ?>
									<td><?php echo smarty_function_wbs_label(array('for' => 'type0','text' => ((is_array($_tmp=((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['kernelStrings']['sei_default_label'])) ? $this->_run_mod_handler('cat', true, $_tmp, " (") : smarty_modifier_cat($_tmp, " (")))) ? $this->_run_mod_handler('cat', true, $_tmp, $this->_tpl_vars['kernelStrings']['sei_browserclose_label']) : smarty_modifier_cat($_tmp, $this->_tpl_vars['kernelStrings']['sei_browserclose_label'])))) ? $this->_run_mod_handler('cat', true, $_tmp, ")") : smarty_modifier_cat($_tmp, ")")),'skipColon' => true), $this);?>
</td>
								<?php endif; ?>
								<td colspan="2"><input type="radio" id="type0" name="periodType[type]" value=0 <?php echo smarty_function_wbs_checkboxStatus(array('val' => $this->_tpl_vars['periodType']['type'],'true_val' => 0), $this);?>
/></td>
							</tr>
							<tr>
								<td><?php echo smarty_function_wbs_label(array('for' => 'type1','text' => $this->_tpl_vars['kernelStrings']['sei_never_label'],'skipColon' => true), $this);?>
</td>
								<td colspan="2"><input type="radio" id="type1" name="periodType[type]" value=1 <?php echo smarty_function_wbs_checkboxStatus(array('val' => $this->_tpl_vars['periodType']['type'],'true_val' => 1), $this);?>
/></td>
							</tr>
							<tr>
								<td><?php echo smarty_function_wbs_label(array('for' => 'type2','text' => $this->_tpl_vars['kernelStrings']['sei_custom_label'],'skipColon' => true), $this);?>
</td>
								<td><input type="radio" id="type2" name="periodType[type]" value=2 <?php echo smarty_function_wbs_checkboxStatus(array('val' => $this->_tpl_vars['periodType']['type'],'true_val' => 2), $this);?>
/></td>
								<td>
									<input onChange="setPeriodType(2)" type=text name="periodType[period]" value="<?php echo ((is_array($_tmp=$this->_tpl_vars['periodType']['period'])) ? $this->_run_mod_handler('htmlsafe', true, $_tmp, true, true) : smarty_modifier_htmlsafe($_tmp, true, true)); ?>
" size=10 class="FormControl"/>
								</td>
							</tr>
						</table>
					</dd>
				</dl>

				<?php $this->_tag_stack[] = array('wbs_buttonSet', array()); smarty_block_wbs_buttonSet($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>
					<?php echo smarty_function_wbs_buttonSetButton(array('caption' => $this->_tpl_vars['kernelStrings']['sys_apply_btn'],'name' => 'savebtn'), $this);?>

					<?php echo smarty_function_wbs_buttonSetButton(array('caption' => $this->_tpl_vars['kernelStrings']['app_cancel_btn'],'name' => 'cancelbtn'), $this);?>

				<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_wbs_buttonSet($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>

			<?php endif; ?>

		<?php $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_wbs_pageLayout($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>
	</body>
</html>