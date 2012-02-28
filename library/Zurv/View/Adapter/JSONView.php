<?php
namespace Zurv\View\Adapter;

use \Zurv\View\Adapter as Adapter;

/**
 * ViewAdapter for json encoded requests
 * 
 * @author mau
 */
class JSONView implements Adapter {
	public function render(array $vars) {
		header('Content-Type: application/json; charset="utf8"');
		
		return json_encode($vars);
	}
}