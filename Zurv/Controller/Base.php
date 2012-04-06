<?php
namespace Zurv\Controller;

use \Zurv\Application;
use \Zurv\Controller;
use \Zurv\Request;
use \Zurv\Response;
use \Zurv\View\View;
use \Zurv\View\Adapter\Factory as AdapterFactory;
use \Zurv\View\Adapter\FileView;
use \Zurv\View\Adapter\JSONView;

class Base implements Controller {
  /**
   * @var \Zurv\Request
   */
  protected $_request;

  /**
   * @var \Zurv\Response
   */
  protected $_response;

  /**
   * @var \Zurv\Application
   */
  protected $_application;

  /**
   * @var \Zurv\View
   */
  protected $_view;

  /**
   * Name of the view directory. Must be a subfolder of the application path.
   * Must end with a slash.
   *
   * @var string
   */
  protected $_viewsPath = 'views/';

  /**
   * If this member variable is overwritten, this file will be used as the
   * template instead of auto picking.
   *
   * @var string
   */
  protected $_template = '';

  public function __construct() {
    $this->_application = Application::getInstance();

    $this->_request = $this->_application->getRequest();
    $this->_response = $this->_application->getResponse();

    $this->_initView();
  }

  /**
   * @return \Zurv\Request
   */
  public function getRequest() {
    return $this->_request;
  }

  /**
   * @return \Zurv\Response
   */
  public function getResponse() {
    return $this->_response;
  }

  /**
   * @return \Zurv\Application
   */
  public function getApplication() {
    return $this->_application;
  }

  /**
   * @return \Zurv\View
   */
  protected function _loadView($view) {
    $applicationPath = $this->getApplication()->getPath();
    $file = $applicationPath . $this->_viewsPath . $view;

    $adapter = AdapterFactory::create(AdapterFactory::FILE, $file);
    return new View($adapter);
  }

  /**
   * Handle view initialization. This means determining which adapter to use
   * and in case a file has to be loaded, determine which.
   *
   * @return void
   */
  protected function _initView() {
    $adapter = null;
    if($this->getRequest()->isAjaxRequest()) {
      $adapter = AdapterFactory::create(AdapterFactory::JSON);
    }
    else {
      $applicationPath = $this->getApplication()->getPath();

      $file = $applicationPath . $this->_viewsPath . $this->_template;
      if(empty($this->_template)) {
        $controller = strtolower($this->getRequest()->getController());
        $action = strtolower($this->getRequest()->getAction());

        $file = "{$applicationPath}{$this->_viewsPath}{$controller}/{$action}.php";
      }

      $adapter = AdapterFactory::create(AdapterFactory::FILE, $file);
    }

    $this->_view = new View($adapter);
  }
}