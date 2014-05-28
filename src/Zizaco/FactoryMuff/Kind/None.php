<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class None extends Kind
{
  public function generate()
  {
    return $this->kind;
  }
}