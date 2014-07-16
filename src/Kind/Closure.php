<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Closure
 *
 * @package League\FactoryMuffin\Kind
 */
class Closure extends Kind
{
    /**
     * @return mixed
     */
    public function generate()
    {
        $kind = $this->kind;

        return $kind();
    }
}
