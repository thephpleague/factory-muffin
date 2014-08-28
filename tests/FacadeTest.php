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
        $original = FactoryMuffin::setSaveMethod('save');

        $new = FactoryMuffin::reset();

        $this->assertNotSame($original, $new);
    }
}
