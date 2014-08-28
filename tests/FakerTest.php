<?php

use League\FactoryMuffin\Facades\Faker;

/**
 * @group faker
 */
class FakerTest extends AbstractTestCase
{
    public function testGetFaker()
    {
        $original = Faker::getFaker();
        $new = Faker::setLocale('en_GB')->getFaker();

        $this->assertInstanceOf('Faker\Generator', $original);
        $this->assertInstanceOf('Faker\Generator', $new);

        $this->assertFalse($original === $new);
    }

    public function testProviders()
    {
        $this->assertInternalType('array', $array = Faker::getProviders());
        $this->assertSame(Faker::getFaker(), Faker::addProvider($array[0])->getFaker());
    }

    public function testFormat()
    {
        $this->assertInstanceOf('Closure', Faker::format('foo'));
        $this->assertInstanceOf('Closure', $sentence = Faker::word());
        $this->assertInternalType('string', $sentence());
        $formatter = Faker::getFormatter('numberBetween');
        $this->assertSame(5, $formatter(5, 5));
    }
}
