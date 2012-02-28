<?php
require_once ZURV_BASE_PATH . 'Zurv/Handler/Base.php';

class AppHandler extends \Zurv\Handler\Base {
	protected $_template = 'index.php';
	
	public function get() {
		$this->_view->display();
	}
}