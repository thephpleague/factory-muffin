<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class String extends Kind
{
    public function generate()
    {
        $length = $this->getOption(0, 10);

        // Generate a large amount of text. The reason for this is that
        // faker uses a maximum length, and not an exact length. We then substr this
        $text = str_replace(' ', null, $this->faker->text($length * 3));

        return substr($text, 0, $length);
    }
}
