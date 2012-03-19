<?php
namespace Zurv;

interface Response {
  public function getBody();
  public function setBody($body);

  public function flush();
}