<?php

use League\FactoryMuffin\Arr;
use League\FactoryMuffin\Facades\FactoryMuffin;

/**
 * @group array
 */
class ArrayTest extends AbstractTestCase
{
    public function testGet()
    {
        $array = array('foo' => 'bar', 'baz' => 'hello');

        $this->assertSame('bar', Arr::get($array, 'foo'));
        $this->assertSame('hello', Arr::get($array, 'baz'));
        $this->assertNull(Arr::get($array, 'bar'));
    }

    public function testHas()
    {
        $array = array('foo' => 'bar', 'baz' => 'hello');

        $this->assertTrue(Arr::has($array, 'bar'));
        $this->assertTrue(Arr::has($array, 'hello'));
        $this->assertFalse(Arr::has($array, 'foo'));
    }

    /**
     * @depends testHas
     */
    public function testAddAndRemove()
    {
        $array = array();
        $new = (object) array('baz' => 'hello');

        $this->assertCount(0, $array);
        $this->assertFalse(Arr::has($array, $new));
        Arr::add($array, $new);
        $this->assertCount(1, $array);
        $this->assertTrue(Arr::has($array, $new));
        $new->bar = 'baz';
        $this->assertCount(1, $array);
        $this->assertTrue(Arr::has($array, $new));
        Arr::add($array, $new);
        $this->assertCount(1, $array);
        Arr::remove($array, $new);
        $this->assertCount(0, $array);
    }
}
