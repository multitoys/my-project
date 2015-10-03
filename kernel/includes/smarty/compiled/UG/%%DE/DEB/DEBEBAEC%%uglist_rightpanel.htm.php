<?php /* Smarty version 2.6.9, created on 2015-10-02 13:52:06
         compiled from uglist_rightpanel.htm */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'wbs_splitterPanelHeader', 'uglist_rightpanel.htm', 10, false),array('block', 'wbs_splitterScrollableArea', 'uglist_rightpanel.htm', 31, false),)), $this); ?>
<?php $this->assign('groupContent', $this->_tpl_vars['contacts']);  $this->assign('numDocumentsLabel', $this->_tpl_vars['kernelStrings']['ul_usercount_title']); ?>

<?php $this->assign('rightPanelCaptionControls', null);  $this->assign('rightPanelControls', null); ?>

<?php $this->assign('rightPanelCaptionControls', "../../../common/html/cssbased/pageelements/ajax/catalog_folder.showbtn.htm"); ?>

<?php if (! $this->_tpl_vars['searchString']): ?>
	<?php echo smarty_function_wbs_splitterPanelHeader(array('caption' => $this->_tpl_vars['rightPanelHeader'],'active' => true,'id' => 'RightPanelHeader','captionControls' => $this->_tpl_vars['rightPanelCaptionControls'],'headerControls' => $this->_tpl_vars['rightPanelControls']), $this);?>

<?php endif; ?>

<?php if (! $this->_tpl_vars['statisticsMode']): ?>
	<?php if ($this->_tpl_vars['viewMode'] != 'GRID'): ?>
		<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../../UG/html/cssbased/uglist_listheader.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
	<?php endif; ?>
	<?php $this->assign('gridFile', "../../../UG/html/cssbased/uglist_content.htm");  else: ?>
	<?php $this->assign('gridFile', "../../../UG/html/cssbased/ul_statistics.htm"); ?>

	<?php $this->assign('hideBottomPanel', 1); ?>
	<?php $this->assign('hidePages', 1); ?>
	<?php $this->assign('docsColumnName', $this->_tpl_vars['kernelStrings']['cm_summarydoc_title']);  endif; ?>

<?php $this->_tag_stack[] = array('wbs_splitterScrollableArea', array()); smarty_block_wbs_splitterScrollableArea($this->_tag_stack[count($this->_tag_stack)-1][1], null, $this, $_block_repeat=true);while ($_block_repeat) { ob_start(); ?>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => $this->_tpl_vars['gridFile'], 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
  $_block_content = ob_get_contents(); ob_end_clean(); echo smarty_block_wbs_splitterScrollableArea($this->_tag_stack[count($this->_tag_stack)-1][1], $_block_content, $this, $_block_repeat=false); }  array_pop($this->_tag_stack); ?>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "../../../UG/html/cssbased/uglist_footer.htm", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>