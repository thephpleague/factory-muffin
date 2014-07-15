<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

class Closure extends Kind
{
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
