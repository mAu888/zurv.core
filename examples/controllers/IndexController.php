<?php
class IndexController implements \Zurv\Controller {
  public function indexAction(\Zurv\Request $request, \Zurv\Response $response) {
    $response->setBody(__METHOD__);
  }
}