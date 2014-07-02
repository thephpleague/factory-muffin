<?php

namespace Zizaco\FactoryMuff;

use Zizaco\FactoryMuff\Kind;

/**
* Creates models with random attributes
*
* @package  Zizaco\FactoryMuff
* @author   Zizaco <zizaco@gmail.com>
* @license  MIT
* @link     https://github.com/Zizaco/factorymuff
*/
class FactoryMuff
{

    /**
     * $factories
     *
     * @var array
     *
     * @access private
     */
    private $factories = array();

    /**
     * Creates and saves in db an instance
     * of Model with mock attributes
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return mixed Returns the model instance.
     */
    public function create( $model, $attr = array() )
    {
        $obj = $this->instance( $model, $attr );

        $result = $obj->save();
        if ( !$result ) {

            $message = '';

            if(isset($obj->validationErrors))
            {
                if($obj->validationErrors)
                {
                    $message = $obj->validationErrors.' - ';
                }
            }

            throw new SaveException($message.'Could not save the model of type: '.$model);
        }

        return $obj;
    }

    /**
     * Return an instance of the model, which is
     * not saved in the database
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return mixed Returns the model instance.
     */
    public function instance( $model, $attr = array() )
    {
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor( $model, $attr );

        // Create, set, save and return instance
        $obj = new $model();

        foreach ($attr_array as $attr => $value) {
            $obj->$attr = $value;
        }

        return $obj;
    }

    /**
     * Returns an array of mock attributes
     * for the specified model
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @access public
     *
     * @return array Returns an attributes array.
     */
    public function attributesFor( $model, $attr = array() )
    {
        $factory_attrs = $this->getFactoryAttrs($model);

        // Prepare attributes
        foreach ( $factory_attrs as $key => $kind ) {
            if ( ! isset($attr[$key]) ){
                $attr[$key] = $this->generateAttr( $kind, $model );
            }
        }

        return $attr;
    }

    /**
     * Define a new model factory
     *
     * @param string $model Model class name.
     * @param array $definition Array with definition of attributes.
     *
     * @access public
     *
     * @return void
     */
    public function define($model, array $definition = array())
    {
        $this->factories[$model] = $definition;
    }

    /**
     * Returns an array with factory definition for the especified model
     *
     * @param string $model Model class name.
     *
     * @access private
     *
     * @return array Returns an factory definition array.
     */
    private function getFactoryAttrs($model)
    {
        if(isset($this->factories[$model])) {
            return $this->factories[$model];
        }

        if(method_exists($model, 'factory'))
        {
            return $model::factory();
        }
        else {
            // Get the $factory static and check for errors
            $static_vars = get_class_vars( $model );

            if (isset( $static_vars['factory'] ) ) {
                return $static_vars['factory'];
            }
        }

        throw new NoDefinedFactoryException('Factory not defined for class: ' . $model);
    }

    /**
     * Generate an attribute based in the wordlist
     *
     * @param string $kind The kind of attribute that will be generate.
     * @param string $model The name of the model class
     *
     * @access private
     *
     * @return mixed String or an instance of related model.
     */
    public function generateAttr( $kind, $model = NULL )
    {
        $kind = Kind::detect($kind, $model);
        return $kind->generate();
    }
}
