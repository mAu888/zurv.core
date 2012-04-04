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

  protected $_dispatcher = null;
  protected $_request = null;
  protected $_response = null;
  protected $_router = null;

  static protected $_instance = null;
  
  protected function __construct($options = array()) {
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
  }
  
  /**
   * The singleton
   */
  static public function getInstance() {
    if(is_null(static::$_instance)) {
      $args = func_get_arg(0);
      if(! is_array($args)) {
        $args = array();
      }

      static::$_instance = new self($args);
    }

    return static::$_instance;
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

  public function run() {
    $request = $this->getRequest();
    $response = $this->getResponse();

    // Bootstrap the application
    $this->bootstrap();

    $this->getRouter()->route($this->getRequest());
    $this->getDispatcher()->dispatch($request, $response);

    // Finish the request by flushing the response
    $response->flush();
  }

  /**
   * Returns the path to the application.
   *
   * @return string
   */
  public function path() {
    return realpath($this->_options['applicationPath']) . '/';
  }

  public function getDispatcher() {
    if(is_null($this->_dispatcher)) {
      $this->_dispatcher = new Dispatcher();
    }

    return $this->_dispatcher;
  }

  public function getRequest() {
    if(is_null($this->_request)) {
      $this->_request = new Request\HTTP();
    }

    return $this->_request;
  }

  public function getResponse() {
    if(is_null($this->_response)) {
      $this->_response = new Response\HTTP();
    }

    return $this->_response;
  }

  public function getRouter() {
    if(is_null($this->_router)) {
      $this->_router = new Router\Base();
    }

    return $this->_router;
  }

  /**
   * Returns the application path
   *
   * @return string
   */
  public function getPath() {
    return $this->_options['applicationPath'];
  }

/**
   * Returns the library path
   *
   * @return string
   */
  public function getLibraryPath() {
    return $this->_options['libraryPath'];
  }

  /**
   * The basic autoloader
   */
  public function autoloader($class) {
    if(strpos($class, '\\') !== false) {
      $className = substr($class, strrpos($class, '\\') + 1); 
    }
    
    $filePath = str_replace('\\', '/', $class);
    $filePath = "{$this->getLibraryPath()}{$filePath}.php";
    if(file_exists($filePath)) {
      require_once $filePath;
    }
  }

  /**
   * Return the registry instance for the application
   *
   * @return \Zurv\Registry
   */
  public function getRegistry() {
    if(is_null($this->_registry)) {
      $this->_registry = Registry::getInstance();
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