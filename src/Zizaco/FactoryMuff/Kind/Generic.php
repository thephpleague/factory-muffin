<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Generic extends Kind
{
  /**
   * We attempt to use Faker for any string passed in, if a Faker property does
   * not exist, then we just return the original string
   * @return mixed
   */
  public function generate()
  {
    $faker = \Faker\Factory::create();

    // Only try and use Faker when there are no spaces in the string
    if (strpos($this->kind, ' ') === false)
    {
      return $faker->{$this->kind} ?: $this->kind;
    }

    return $this->kind;
  }
}