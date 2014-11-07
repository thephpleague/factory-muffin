<?php

use League\FactoryMuffin\Arr;

/**
 * @group array
 */
class ArrayTest extends AbstractTestCase
{
    public function testGet()
    {
        $array = ['foo' => 'bar', 'baz' => 'hello'];

        $this->assertSame('bar', Arr::get($array, 'foo'));
        $this->assertSame('hello', Arr::get($array, 'baz'));
        $this->assertNull(Arr::get($array, 'bar'));
    }

    public function testHas()
    {
        $array = ['foo' => 'bar', 'baz' => 'hello'];

        $this->assertTrue(Arr::has($array, 'bar'));
        $this->assertTrue(Arr::has($array, 'hello'));
        $this->assertFalse(Arr::has($array, 'foo'));
    }
}
