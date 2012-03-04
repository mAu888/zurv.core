<?php
namespace Zurv;

class Application {
	/**
	 * Options for the application
	 *
	 * @var array
	 */
	protected $_options = array(
		'applicationPath' => '',
		'libraryPath' => 'library/',
		'bootstrapperClass' => ''
	);

	/**
	 * Single registry instance for the application
	 *
	 * @var \Zurv\Registry
	 */
	protected $_registry = null;
	
	public function __construct($options = array()) {
		$this->_options = array_merge($this->_options, $options);
		
		if(isset($this->_options['registry'])) {
			if($this->_options['registry'] instanceof Registry) {
				$this->setRegistry($this->_options['registry']);
			}
			else if(class_exists($this->_options['registry'])) {
				$this->setRegistry(new $this->_options['registry']);
			}
		}

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

	/**
	 * Returns the path to the application.
	 *
	 * @return string
	 */
	public function path() {
		return realpath($this->_options['applicationPath']) . '/';
	}

	/**
	 * The basic autoloader
	 */
	public function autoloader($class) {
		if(strpos($class, '\\') !== false) {
			$className = substr($class, strrpos($class, '\\') + 1);	
		}
		
		$filePath = str_replace('\\', '/', $class);
		$filePath = "{$this->_options['libraryPath']}/{$filePath}.php";
		if(file_exists($filePath)) {
			require_once $filePath;
		}else {echo "$filePath";}
	}

	/**
	 * Return the registry instance for the application
	 *
	 * @return \Zurv\Registry
	 */
	public function getRegistry() {
		if(is_null($this->_registry)) {
			$this->_registry = new Registry();
		}

		return $this->_registry;
	}

	/** 
	 * Set the registry instance for the application
	 *
	 * @param \Zurv\Registry
	 */
	public function setRegistry(Registry $r) {
		$this->_registry = $r;
	}
}