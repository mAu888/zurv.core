<?php
namespace Zurv\View;

interface Adapter {
	/**
	 * Render the view
	 * 
	 * @param array $vars
	 */
	function render(array $vars);
}