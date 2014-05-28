<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Closure extends Kind
{
  public function generate()
  {
    $kind = $this->kind;
    return $kind();
  }
}