<?php
namespace Zurv;

interface Response {
  public function getBody();
  public function setBody($body);

  public function setHeader($header, $value);

  public function flush();
}