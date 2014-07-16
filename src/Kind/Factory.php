<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;

/**
 * Class Factory
 *
 * @package League\FactoryMuffin\Kind
 */
class Factory extends Kind
{
    /**
     * @var array
     */
    private $methods = array(
        'getKey',
        'pk',
    );

    /**
     * @var array
     */
    private $properties = array(
      'id',
      '_id'
    );

    /**
     * @return null
     */
    public function generate()
    {
        $factory = new \League\FactoryMuffin\FactoryMuffin;
        $model = $factory->create(substr($this->kind, 8));
        return $this->getId($model);
    }

    /**
     * @param $model
     * @return null
     */
    private function getId($model)
    {
        // Check to see if we can get an ID via our defined methods
        foreach ($this->methods as $method) {
            if (method_exists($model, $method)) {
                return $model->$method();
            }
        }

        // Check to see if we can get an ID via our defined methods
        foreach ($this->properties as $property) {
            if (isset($model->$property)) {
                return $model->$property;
            }
        }

        // We cannot find an ID
        return null;
    }
}
