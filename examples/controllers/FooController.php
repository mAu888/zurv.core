<?php
class FooController implements \Zurv\Controller {
  public function indexAction(\Zurv\Request $request, \Zurv\Response $response) {
    $response->setBody(__METHOD__);
  }

  public function barAction(\Zurv\Request $request, \Zurv\Response $response) {
    $response->setBody(__METHOD__);
  }
}