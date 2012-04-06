<?php
namespace Zurv;

interface Request {
  public function getAction();
  public function getController();
  public function getParameter($parameter);
  public function getPath();
  public function getRequestMethod();
  public function getExtension();

  public function hasParameter($parameter);

  public function setAction($action);
  public function setController($controller);
  public function setParameter($parameter, $value);
  public function setExtension($extension);

  public function isXmlHttpRequest();
}