<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Integer extends Kind
{
  public function generate()
  {
    $length = substr($this->kind, 8);
    $faker = \Faker\Factory::create();
    return $faker->randomNumber($length);
  }
}