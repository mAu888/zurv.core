<?php
class AppHandler extends BaseHandler {
	protected $_template = 'index.php';
	
	public function get() {
		$this->_view->display();
	}
}