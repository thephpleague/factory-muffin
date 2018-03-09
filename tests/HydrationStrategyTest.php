<?php

use League\FactoryMuffin\HydrationStrategies\PublicSetterHydrationStrategy;
use League\FactoryMuffin\HydrationStrategies\ReflectionHydrationStrategy;
use Prophecy\Argument;

class HydrationStrategyTest extends AbstractTestCase
{
    public function testHydrationStrategyIsBeingUsed()
    {
        $strategy = $this->prophesize('League\FactoryMuffin\HydrationStrategies\HydrationStrategyInterface');

        static::$fm->setHydrationStrategy('FakerHydrationModel', $strategy->reveal());
        static::$fm->instance('FakerHydrationModel');

        $strategy->set(Argument::type('FakerHydrationModel'), Argument::type('string'), Argument::any())
            ->shouldBeCalledTimes(3);
    }

    public function testPublicSetterHydration()
    {
        $strategy = new PublicSetterHydrationStrategy();

        $model = new ModelWithPublicSetters();

        $strategy->set($model, 'value', 'Test value');
        $strategy->set($model, 'separated_value', 'Another test value');

        $this->assertEquals($model->getValue(), 'Test value');
        $this->assertEquals($model->getSeparatedValue(), 'Another test value');
    }

    public function testPublicAttributesHydration()
    {
        $strategy = new PublicSetterHydrationStrategy();

        $model = new ModelWithPublicAttributes();

        $strategy->set($model, 'value', 'Test value');
        $strategy->set($model, 'separated_value', 'Another test value');

        $this->assertEquals($model->value, 'Test value');
        $this->assertEquals($model->separated_value, 'Another test value');
    }

    public function testProtectedAttributesHydrationByReflection()
    {
        $strategy = new ReflectionHydrationStrategy();

        $model = new ModelWithProtectedAttributes();

        $strategy->set($model, 'value', 'Test value');
        $strategy->set($model, 'separated_value', 'Another test value');

        $this->assertEquals($model->getValue(), 'Test value');
        $this->assertEquals($model->getSeparatedValue(), 'Another test value');
    }
}

class ModelWithPublicAttributes
{
    public $value;
    public $separated_value;
}

class ModelWithProtectedAttributes
{
    protected $value;
    protected $separated_value;

    public function getValue()
    {
        return $this->value;
    }

    public function getSeparatedValue()
    {
        return $this->separated_value;
    }
}

class ModelWithPublicSetters extends ModelWithProtectedAttributes
{
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function setSeparatedValue($separated_value)
    {
        $this->separated_value = $separated_value;
    }
}

class FakerHydrationModel
{
    public $title;
    public $email;
    public $text;
}
