<?php
namespace Zurv\View;
/**
 * Class for handling views
 * 
 * @author mau
 */
class View {
	/**
	 * @var array
	 */
	protected $_vars = array();
	
	/**
	 * @var ViewAdapter
	 */
	protected $_viewAdapter = null;
	
	public function __construct(Adapter $adapter = null) {
		$this->_viewAdapter = $adapter;
	}
	
	public function __get($key) {
		if(! array_key_exists($key, $this->_vars)) {
			return null;
		}
		
		return $this->_vars[$key];
	}
	
	public function __set($key, $value) {
		$this->_vars[$key] = $value;
	}
	
	public function render(array $vars = array()) {
		$vars = array_merge($this->_vars, $vars);
		
		$render = $this->_viewAdapter->render($vars);
		
		return $render;
	}
	
	public function display(array $vars = array()) {
		echo $this->render($vars);
	}
	
	public function getAdapter() {
		return $this->_viewAdapter;
	}
	
	public function setAdapter(\Zurv\View\Adapter $adapter) {
		$this->_viewAdapter = $adapter;
	}
	
	public function __toString() {
		return $this->render();
	}
}