<?php
namespace Zurv\Handler;

use \Zurv\Registry;
use \Zurv\View\View;
use \Zurv\View\Adapter\Factory as AdapterFactory;

class Base extends \ToroHandler {
	protected $_db = null;
	
	/**
	 * @var \Zurv\View\View
	 */
	protected $_view = null;
	protected $_template = '';
	
	protected $_isAjax = false;
	
	public function __construct() {
		parent::__construct();

		$this->_db = Registry::getInstance()->db;
		
		$adapter = null;
		if(isset($this->_template)) {
			// TODO: Get the base path from the application object
			$this->_template = APP_BASE_PATH . (strpos($this->_template, '.') !== false ?
				"views/{$this->_template}" : "views/{$this->_template}.php");
			
			$adapter = AdapterFactory::create(AdapterFactory::FILE, $this->_template);
		}
		
		$this->_view = new View($adapter);
	}
	
	public function ajax($isAjax = true) {
		$adapter = null;
		if($isAjax) {
			$adapter = AdapterFactory::create(AdapterFactory::JSON);
		}
		else {
			$adapter = AdapterFactory::create(AdapterFactory::FILE, $this->_template);
		}
		
		$this->_view->setAdapter($adapter);
	}
}