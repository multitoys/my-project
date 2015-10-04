<?php

class LocalizationCompiler 
{
	public $domains = array(
		'ru' => 'webasyst',
		'en' => 'webasyst',
		'ge' => 'webasyst'
	);
	// \t - tab
	public $source_path = "H:\wamp\www\lc\\test\source";
	public $backup_path = "H:\wamp\www\lc\\test\source\original";
	public $compile_path = "H:\wamp\www\lc\\test";
		
	public $files_include = ".+\.(tpl|js|htm|html)";
	public $files_compile = ".+\.(tpl|js|htm|html)";
	public $files_words = ".+\.(tpl|html)";
	public $dirs_exclude = "(\.svn)";
	
	public $split_on_subfolder = true;

	public $locale_path = "H:\wamp\www\lc\\test\locale";
	
	public $debug = true;
	
	// Flags
	public $update_files = false;
	public $update_locale = false;
	public $update_complile = true;
	
	public $recursive = true; 
	
	public function __construct() 
	{

	}
	
	public function exec()
	{
		// Get files
		$lf = new LocalizationFiles();
		$lf->recursive = $this->recursive;
		$lf->dirs_exclude = $this->dirs_exclude;
		$lf->files_include = $this->files_include;
		$files = $lf->getFiles($this->source_path);
		
		$ld = new LocalizationDictionary($this->locale_path);
		$ld->domains = $this->domains;
		
		foreach ($files as $file) {
			$file_path = $this->source_path.$file[0].$file[1];
			// Find new words and mark them
			if ($this->update_files && preg_match("/^".$this->files_words."$/ui", $file[1])) {
				$text = file_get_contents($file_path);
				$lp = new LocalizationPrepare($text);
				$lf->save($this->backup_path.$file[0].$file[1], $text);
				$lf->save($file_path, $lp->exec());
			}
			// Find all word and save them in localization dictionary
			if ($this->update_locale) {
				$ld->getWords($file_path);
			}
			
			if ($this->update_complile && preg_match("/^".$this->files_compile."$/ui", $file[1])) {
				if ($file[0] && $this->split_on_subfolder) {
					$file[0] = preg_replace("/^(\\".DIRECTORY_SEPARATOR."[^\\".DIRECTORY_SEPARATOR."]*\\".DIRECTORY_SEPARATOR.")/ui", "$1:LANG:".DIRECTORY_SEPARATOR, $file[0]);
				} else {
					$file[0] .= ":LANG:" . DIRECTORY_SEPARATOR;
				}
				$ld->translateFile($file_path, $this->compile_path.$file[0].$file[1]);				
			}
		}
		
		if ($this->update_locale) {
			$ld->save();
		}
	}	
	
}

?>