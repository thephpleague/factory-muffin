<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Text extends Kind
{
  public function generate()
  {
    $faker = \Faker\Factory::create();
    $length = $this->getOption(0, 100);

    // Generate a large amount of text. The reason for this is that
    // faker uses a maximum length, and not an exact length. We then substr this
    $text = $faker->text($length * 3);
    return substr($text, 0, $length);
  }
}