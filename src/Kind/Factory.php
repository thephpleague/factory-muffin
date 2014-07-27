<?php

namespace League\FactoryMuffin\Kind;

use League\FactoryMuffin\Kind;
use League\FactoryMuffin\Facade\FactoryMuffin;

/**
 * Class Factory.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Factory extends Kind
{
    /**
     * The factory methods.
     *
     * @type array
     */
    private $methods = array(
        'getKey',
        'pk',
    );

    /**
     * The factory properties.
     *
     * @type array
     */
    private $properties = array(
      'id',
      '_id'
    );

    /**
     * Return generated data.
     *
     * @return int
     */
    public function generate()
    {
        $model = FactoryMuffin::create(substr($this->kind, 8));
        return $this->getId($model);
    }

    /**
     * Get the model id.
     *
     * @param string $model Model class name.
     *
     * @return int The model id if available.
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
    }
}
