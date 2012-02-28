<?php
namespace Zurv\Model;

interface Entity {
	/**
	 * Check, if an entity has a property
	 * 
	 * @param string $key
	 * @return bool
	 */
	function has($key);
	
	/**
	 * Convert the entity object to an associative array
	 * 
	 * @return array
	 */
	function toArray();
}