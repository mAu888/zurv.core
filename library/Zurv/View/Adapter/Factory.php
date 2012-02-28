<?php
namespace Zurv\View\Adapter;

/**
 * Factory for creating ViewAdapters
 * 
 * @author mau
 */
class Factory {
	const FILE = 'file';
	const JSON = 'json';
	
	public static function create() {
		$args = func_get_args();
		$adapter = null;
		
		$type = array_shift($args);
		switch($type) {
			case self::FILE:
				$adapter = new \ReflectionClass('\Zurv\View\Adapter\FileView');
				$adapter = $adapter->newInstanceArgs($args);
				break;
			case self::JSON:
				$adapter = new JSONView();
				break;
			default:
				throw new \InvalidArgumentException("Could not load ViewAdapter for type {$type}");
				break;
 		}
 		
 		return $adapter;
	}
}