<?php
namespace Zurv;

class Application {
	protected $_options = array(
		'library' => 'library/',
		'bootstrapperClass' => ''
	);
	
	public function __construct($options = array()) {
		$this->_options = array_merge($this->_options, $options);
		
		/**
		 * Register the autoloader function
		 */
		spl_autoload_register(array($this, 'autoloader'), true);
		
		/**
		 * Push put and delete request variables into $_POST.
		 */
		\ToroHook::add('before_request', function() {
			$input = json_decode(file_get_contents('php://input'), true);
			switch(strtolower($_SERVER['REQUEST_METHOD'])) {
				case 'get':
				case 'post': break;
				case 'put': $_POST = $input; break;
				case 'delete': $_POST = $input; break;
				default: throw new Exception('Invalid request type'); break;
			}
			
		});
		
		/**
		 * Close the database connection.
		 */
		\ToroHook::add('after_request', function() {
			Registry::getInstance()->db = null;
		});
	}
	
	/**
	 * Runs the configured bootstrapper 
	 *
	 * @return \Zurv\Application
	 */
	public function bootstrap() {
		if(class_exists($this->_options['bootstrapperClass'])) {
			$bootstrapper = new $this->_options['bootstrapperClass']($this);

			if($bootstrapper instanceof Bootstrapper) {
				$methods = get_class_methods($bootstrapper);

				foreach($methods as $method) {
					if(strpos($method, 'init') === 0) {
						$bootstrapper->bootstrap($method);
					}
				}
			}
		}

		return $this;
	}

	public function run($routes) {
		$toro = new \ToroApplication($routes);
		$toro->serve();
	}
	
	public function autoloader($class) {
		if(strpos($class, '\\') !== false) {
			$className = substr($class, strrpos($class, '\\') + 1);	
		}
		
		$filePath = str_replace('\\', '/', $class);
		$filePath = "{$this->_options['library']}/{$filePath}.php";
		if(file_exists($filePath)) {
			require_once $filePath;
		}else {echo "$filePath";}
	}
}