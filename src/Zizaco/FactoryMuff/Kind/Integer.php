<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Integer extends Kind
{
  public function generate()
  {
    $length = (int) $this->getOption(0, 5);
    return $this->randomNumber($length);
  }

  private function randomNumber($length)
  {
    $integer = null;
    for($i = 0; $i < $length; $i++) {
      $integer .= mt_rand(1, 9);
    }

    return (int) $integer;
  }
}