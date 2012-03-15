<?php
namespace Zurv;

interface Request {
  public function getAction();
  public function getController();
  public function getParamter($parameter);

  public function setAction($action);
  public function setController($controller);
  public function setParameter($parameter, $value);
}