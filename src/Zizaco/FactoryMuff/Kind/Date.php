<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Date extends Kind
{
  public function generate()
  {
    $format = substr($this->kind, 5);
    $faker = \Faker\Factory::create();
    return $faker->date($format);
  }
}