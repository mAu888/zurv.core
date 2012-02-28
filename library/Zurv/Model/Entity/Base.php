<?php
namespace Zurv\Model\Entity;

use \Zurv\Model\Entity as Entity;

abstract class Base implements Entity, \ArrayAccess {
	protected $_attributes = array();
	
	/**
	 * Constructor.
	 * @param array $seed Optionally set the seed values
	 */
	public function __construct($seed = array()) {
		if(! empty($seed)) {
			$this->_setAttributes($seed);
		}
	}
	
	/**
	 * Convenience method for setting multiple attributes the same time
	 * @param array $attributes
	 */
	protected function _setAttributes($attributes) {
		foreach($attributes as $key => $value) {
			$this->{'set' . ucfirst($key)}($value);
		}
	}
	
	/**
	 * Returns the entity data as array
	 * 
	 * @return array
	 */
	public function toArray() {
		return $this->_attributes;
	}
	
	/**
	 * Checks, if two entities are equal by values
	 * 
	 * @param Entity $e
	 */
	public function equals(Entity $e) {
		foreach($e->toArray() as $key => $value) {
			if(! $this->has($key)) {
				return false;
			}
			
			if($this[$key] !== $value) {
				return false;
			}
		}
		
		return true;
	}
	
	protected function _toArray($array) {
		$return = array();
		
		foreach($array as $key => $value) {
			if(is_object($value) && method_exists($value, "toArray")) {
				$value = $value->toArray();
			}
			
			$return[$key] = $value;
		}
		
		return $return;
	}
	
	/**
	 * Magig getters and setters.
	 * @param string $method
	 * @param array $params
	 * @throws \BadMethodCallException
	 */
	public function __call($method, $params) {
		$do = strtolower(substr($method, 0, 3));
		$var = substr($method, 3);
		$var = $this->_getKey($var);
		switch($do) {
			case 'get':
				return $this->_getAttribute($var);
				break;
			case 'set':
				if(empty($params)) {
					throw new \BadMethodCallException('Missing parameter to set');
				}
				
				$this->_setAttribute($var, array_pop($params));
				break;
			case 'is':
				return $this->_getAttribute($var) ? true : false;
				break;
			default:
				throw new \BadMethodCallException("Could not invoke method {$method}");
			break;
		}
	}
	
	/**
	 * Check for a given attribute.
	 * @param string $key
	 */
	public function has($key) {
		return array_key_exists($key, $this->_attributes);
	}
	
	/**
	 * Get class attribute.
	 * @param string $key
	 * @throws \BadMethodCallException
	 */
	protected function _getAttribute($key) {
		if(! $this->has($key)) {
			throw new \BadMethodCallException('Class' . __CLASS__ . ' has no attribute ' . $key);
		}
		
		return $this->_attributes[$key];
	}
	
	/**
	 * Set class attribute.
	 * @param string $key
	 * @param mixed $param
	 * @throws \BadMethodCallException
	 */
	protected function _setAttribute($key, $param) {
		if(! $this->has($key)) {
			throw new \BadMethodCallException('Class' . __CLASS__ . ' has no attribute ' . $key);
		}
		
		$this->_attributes[$key] = $param;
	}
	
	/**
	 * Convert a camel cased key to a underscored one. E.g. testKey converts to test_key
	 * 
	 * @param string $key
	 * @return string
	 */
	protected function _getKey($key) {
		return strtolower(substr(preg_replace('/([A-Z])/', '_\1', $key), 1));
	}
	
	public function offsetExists($key) {
		return isset($this->_attributes[$key]);
	}
	
	public function offsetGet($key) {
		return $this->_attributes[$key];
	}
	
	public function offsetSet ($key, $value) {
		if(array_key_exists($key, $this->_attributes)) {
			$this->_attributes[$key] = $value;
		}
		else {
			throw new \OutOfBoundsException("Invalid access to attribute {$key}");
		}
	}
	
	public function offsetUnset ($key) {
		$this->_attributes[$key] = null;
	}
}