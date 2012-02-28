<?php
require_once 'library/Zurv/Handler/Base.php';

class AppHandler extends \Zurv\Handler\Base {
	protected $_template = 'index.php';
	
	public function get() {
		$this->_view->display();
	}
}