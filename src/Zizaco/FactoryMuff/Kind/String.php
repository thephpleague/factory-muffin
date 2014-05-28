<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class String extends Kind
{
  public function generate()
  {
    $faker = \Faker\Factory::create();
    return $faker->word;
  }
}