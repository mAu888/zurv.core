<?php
class FooController implements \Zurv\Controller {
  public function barAction(\Zurv\Request $request, \Zurv\Response $response) {
    $response->setBody('Fuckin freaky');
  }
}