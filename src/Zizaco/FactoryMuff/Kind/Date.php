<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Date extends Kind
{
  public function generate()
  {
    $format = $this->getOption(0, 'r');
    $faker = \Faker\Factory::create();
    return $faker->date($format);
  }
}