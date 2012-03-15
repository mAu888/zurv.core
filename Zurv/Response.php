<?php
namespace Zurv;

interface Response {
  public function getBody();
  public function setBody($body);
}