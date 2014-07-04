<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Name extends Kind
{
  public function generate()
  {
    $gender = $this->getOption(0, null);
    $faker = \Faker\Factory::create();
    return $faker->name($gender);
  }
}