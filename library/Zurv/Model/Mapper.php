<?php
namespace Zurv\Model;

/**
 * Mapper interface
 */
interface Mapper {
	/**
	 * Find entity by id
	 * @param mixed $id
	 */
	function findById($id);
	
	/**
	 * Find entity by attribute
	 * 
	 * @param string $attribute
	 * @param mixed $value
	 */
	function findByAttribute($attribute, $value);
}