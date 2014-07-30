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
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Factory extends Kind
{
    /**
     * Generate, and return the attribute.
     *
     * @type string[]
     */
    private $methods = array('getKey', 'pk');

    /**
     * The factory properties.
     *
     * @type string[]
     */
    private $properties = array('id', '_id');

    /**
     * Return generated data.
     *
     * @return int
     */
    public function generate()
    {
        $factory_name = substr($this->kind, 8);

        if ($this->save) {
            $model = FactoryMuffin::create($factory_name);
        } else {
            $model = FactoryMuffin::instance($factory_name);
        }

        return $this->getId($model);
    }

    /**
     * Get the model id.
     *
     * @param string $model Model class name.
     *
     * @return int
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
