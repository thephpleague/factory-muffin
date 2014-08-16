<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @group facade
 */
class FacadeTest extends AbstractTestCase
{
    public function testReset()
    {
        // this is a bit hacky, but works because of the method chaining
        $original = FactoryMuffin::setFakerLocale('en_GB');

        $new = FactoryMuffin::reset();

        $this->assertNotSame($original, $new);
    }
}
