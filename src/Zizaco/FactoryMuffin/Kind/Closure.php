<?php

namespace Zizaco\FactoryMuffin\Kind;

use Zizaco\FactoryMuffin\Kind;

class Closure extends Kind
{
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
