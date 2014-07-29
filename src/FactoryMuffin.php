<?php

namespace League\FactoryMuffin;

use League\FactoryMuffin\Exception\DirectoryNotFound;
use League\FactoryMuffin\Exception\NoDefinedFactory;
use League\FactoryMuffin\Exception\DeleteMethodNotFound;
use League\FactoryMuffin\Exception\Save;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class FactoryMuffin.
 *
 * @package League\FactoryMuffin
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryMuffin
{
    /**
     * The array of factories.
     *
     * @type array
     */
    private $factories = array();

    /**
     * The array of objects we have created.
     *
     * @type array
     */
    private $saved = array();

    /**
     * This is the method used when deleting objects.
     *
     * @type string
     */
    private $deleteMethod = 'delete';

    /**
     * Creates and saves in db an instance of Model with mock attributes.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @throws \League\FactoryMuffin\Exception\Save
     *
     * @return object
     */
    public function create($model, $attr = array())
    {
        $obj = $this->instance($model, $attr);

        if (!$this->save($obj)) {
            if (isset($obj->validationErrors) && $obj->validationErrors) {
                throw new Save($model, $obj->validationErrors);
            }

            throw new Save($model);
        }

        return $obj;
    }

    /**
     * Save our object to the DB, and keep track of it.
     *
     * @param object $object
     *
     * @return mixed
     */
    public function save($object)
    {
        $result = $object->save();
        $this->saved[] = $object;

        return $result;
    }

    /**
     * Return an instance of the model, which is not saved in the database.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return object
     */
    public function instance($model, $attr = array())
    {
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor($model, $attr);

        // Create, set, save and return instance
        $obj = new $model();

        foreach ($attr_array as $attr => $value) {
            $obj->$attr = $value;
        }

        return $obj;
    }

    /**
     * Returns an array of mock attributes for the specified model.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @return array
     */
    public function attributesFor($model, $attr = array())
    {
        $factory_attrs = $this->getFactoryAttrs($model);

        // Prepare attributes
        foreach ($factory_attrs as $key => $kind) {
            if (!isset($attr[$key])) {
                $attr[$key] = $this->generateAttr($kind, $model);
            }
        }

        return $attr;
    }

    /**
     * Define a new model factory.
     *
     * @param string $model      Model class name.
     * @param array  $definition Array with definition of attributes.
     *
     * @return void
     */
    public function define($model, array $definition = array())
    {
        $this->factories[$model] = $definition;
    }

    /**
     * Get factory attributes.
     *
     * @param string $model Model class name.
     *
     * @throws \League\FactoryMuffin\Exception\NoDefinedFactory
     *
     * @return mixed
     */
    private function getFactoryAttrs($model)
    {
        if (isset($this->factories[$model])) {
            return $this->factories[$model];
        }

        throw new NoDefinedFactory($model);
    }

    /**
     * Generate the attributes and return a string, or an instance of the model.
     *
     * @param string $kind  The kind of attribute that will be generate.
     * @param string $model The name of the model class.
     *
     * @return string|object
     */
    public function generateAttr($kind, $model = null)
    {
        $kind = Kind::detect($kind, $model);

        return $kind->generate();
    }

    /**
     * Load the specified factories.
     *
     * @param string|string[] $paths
     *
     * @return void
     */
    public function loadFactories($paths)
    {
        foreach ((array) $paths as $path) {
            if (!is_dir($path)) {
                throw new DirectoryNotFound($path);
            }

            $this->loadDirectory($path);
        }
    }

    /**
     * Return an array of saved objects.
     *
     * @return object[]
     */
    public function saved()
    {
        return $this->saved;
    }

    /**
     * Call the delete method on any saved objects.
     *
     * @return void
     */
    public function deleteSaved()
    {
        $deleteMethod = $this->deleteMethod;
        foreach ($this->saved() as $saved) {
            if (! method_exists($saved, $deleteMethod)) {
                throw new DeleteMethodNotFound($saved, $deleteMethod);
            }

            $saved->$deleteMethod();
        }
    }

    /**
     * Set the method we use when deleting objects.
     *
     * @param string $method
     *
     * @return void
     */
    public function setDeleteMethod($method)
    {
        $this->deleteMethod = $method;
    }

    /**
     * Load all the files in a directory.
     *
     * @param string $path
     *
     * @return void
     */
    private function loadDirectory($path)
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^.+\.php$/i');

        foreach ($files as $file) {
            include_once $file->getPathName();
        }
    }
}
