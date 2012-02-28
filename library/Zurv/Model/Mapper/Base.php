<?php
namespace Zurv\Model\Mapper;

use Zurv\Model\Mapper as Mapper;

/**
 * Base mapper
 * 
 * @author mau
 */
abstract class Base implements Mapper {
	public function findById($id) {
		throw new Exception('To be implemented');
	}
	
	public function findByAttribute($attribute, $value) {
		throw new Exception('To be implemented');
	}
}