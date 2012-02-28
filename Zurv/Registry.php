<?php
/**
 * @author Maurício Hanika
 * @version 1.0.0
 * @package Zurv
 */
namespace Zurv;

/**
 * Basic registry.
 * 
 * @author mau
 *
 */
class Registry {
	private static $_instance = null;
	
	private $_data = array();
	
	/**
	 * Singleton.
	 */
	private final function __construct() {}
	private final function __clone() {}
	
	public static function getInstance() {
		if(self::$_instance === null) {
			self::$_instance = new self();
		}
		
		return self::$_instance;
	}
	
	/**
	 * Magically get class properties
	 * 
	 * @param mixed $key
	 */
	public function __get($key) {
		if(isset($this->_data[$key])) {
			return $this->_data[$key];
		}
		
		return null;
	}
	
	/**
	 * Magically set class properties
	 * 
	 * @param string $key
	 * @param mixed $value
	 */
	public function __set($key, $value) {
		$this->_data[$key] = $value;
	}
}