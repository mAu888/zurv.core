<?php
namespace Zurv\View\Adapter;

use \Zurv\View\Adapter as Adapter;

/**
 * ViewAdapter for file templates
 * 
 * @author mau
 */
class FileView implements Adapter {
	protected $_template = '';
	
	public function __construct($file) {
		if(! file_exists($file)) {
			throw new \InvalidArgumentException("Could not load view {$file}");
		}
		
		$this->_template = $file;
	}
	
	public function render(array $vars) {
		header('Content-Type: text/html; charset="utf8"');
		
		ob_start();
		extract($vars);
		include $this->_template;
		$render = ob_get_contents();
		ob_end_clean();
		
		return $render;
	}
}