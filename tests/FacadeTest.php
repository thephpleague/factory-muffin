<?php

use League\FactoryMuffin\Facades\FactoryMuffin;
use League\FactoryMuffin\Facades\Faker;

/**
 * @group facade
 */
class FacadeTest extends AbstractTestCase
{
    public function testFactoryMuffinReset()
    {
        // this is a bit hacky, but works because of the method chaining
        $original = FactoryMuffin::setSaveMethod('save');

        $new = FactoryMuffin::reset();

        $this->assertNotSame($original, $new);
    }

    public function testFakerReset()
    {
        // this is a bit hacky, but works because of the method chaining
        $original = Faker::setLocale('en_GB');

        $new = Faker::reset();

        $this->assertNotSame($original, $new);
    }
}
