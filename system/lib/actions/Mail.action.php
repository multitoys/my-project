<?php

abstract class MailAction
{
	const TITLE = 0;
	const VALUE = 1;
	const SHOW = 2;
	const EDIT = 3;
	const TYPE = 4;
	
	protected $app_id;
	protected $fields = array();
	
	public $title = "";
	/**
	 * @var Smarty
	 */
	protected $smarty;
	/**
	 * Path to template
	 * 
	 * @var string
	 */
	protected $template;
	
	public function __construct($app_id, $smarty, $template)
	{
		$this->app_id = $app_id;
		$this->smarty = Registry::get($smarty);
		$this->template = $template;
		
		$this->fields = array(
			'from' => array(_("From"), "", false, false, array("input", "text")),
			'to' => array(_("To"), "", true, true, array("input", "text")),
			'subject' => array(_("Subject"), "", true, true, array("input", "text")),
			'content' => array(_("Content"), "", true, true, array("textarea")),
			//'attachments' => array("", false, false)
		);		
		if (Env::Post('status') == 'send') {
			$this->send();
			$this->onSend();
		}
	}
	
	public function send()
	{
		$message = Mailer::composeMessage($this->app_id);
		foreach ($this->fields as $field => $options) {
			if ($options[self::SHOW]) {
				$method = "add".ucfirst($field);
				if ($field == 'content') {
					$message->$method(nl2br(Env::Post($field)));
				} else {
					$message->$method(Env::Post($field));
				}
			}		
		}
		Mailer::send($message);
	}
	
	public function onSend()
	{
	}
	
	public function prepareData()
	{
		$this->smarty->assign('status', Env::Post('status', Env::TYPE_STRING, ''));
		$this->smarty->assign('fields', $this->fields);		
	}	
	
	public function getResponse()
	{
		$this->prepareData();
		return $this->smarty->fetch($this->template);
	}
}

?>