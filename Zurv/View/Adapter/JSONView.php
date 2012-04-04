<?php
namespace Zurv\View\Adapter;

/**
 * ViewAdapter for json encoded requests
 * 
 * @author mau
 */
class JSONView extends Base {
	public function render(array $vars) {
		header('Content-Type: application/json; charset="utf8"');
		
		return json_encode($vars);
	}
}