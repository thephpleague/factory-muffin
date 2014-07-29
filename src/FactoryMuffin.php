<?php

namespace League\FactoryMuffin;

use Exception;
use League\FactoryMuffin\Exception\DeleteMethodNotFound;
use League\FactoryMuffin\Exception\DeletingFailed;
use League\FactoryMuffin\Exception\DirectoryNotFound;
use League\FactoryMuffin\Exception\NoDefinedFactory;
use League\FactoryMuffin\Exception\SaveFailed;
use League\FactoryMuffin\Exception\SaveMethodNotFound;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * Class FactoryMuffin.
 *
 * @package League\FactoryMuffin
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
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
     * This is the method used when saving objects.
     *
     * @type string
     */
    private $saveMethod = 'save';

    /**
     * Creates and saves in db an instance of Model with mock attributes.
     *
     * @param string $model Model class name.
     * @param array  $attr  Model attributes.
     *
     * @throws \League\FactoryMuffin\Exception\SaveFailed
     *
     * @return object
     */
    public function create($model, $attr = array())
    {
        $obj = $this->instance($model, $attr, true);

        if (!$this->save($obj)) {
            if (isset($obj->validationErrors) && $obj->validationErrors) {
                throw new SaveFailed($model, $obj->validationErrors);
            }

            throw new SaveFailed($model);
        }

        return $obj;
    }

    /**
     * Save our object to the DB, and keep track of it.
     *
     * @param object $object
     *
     * @throws \League\FactoryMuffin\Exception\SaveMethodNotFound
     *
     * @return mixed
     */
    public function save($object)
    {
        $method = $this->saveMethod;

        if (!method_exists($object, $method)) {
            throw new SaveMethodNotFound($object, $method);
        }

        $result = $object->$method();
        $this->saved[] = $object;

        return $result;
    }

    /**
     * Return an instance of the model, which is not saved in the database.
     *
     * @param string $model Model class name.
     * @param bool   $save  Are we saving an object, or just creating an instance?
     * @param array  $attr  Model attributes.
     *
     * @return object
     */
    public function instance($model, $attr = array(), $save = false)
    {
        // Get the factory attributes for that model
        $attr_array = $this->attributesFor($model, $attr, $save);

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
     * @param bool   $save  Are we saving an object, or just creating an instance?
     *
     * @return array
     */
    public function attributesFor($model, $attr = array(), $save = false)
    {
        $factory_attrs = $this->getFactoryAttrs($model);

        // Prepare attributes
        foreach ($factory_attrs as $key => $kind) {
            if (!isset($attr[$key])) {
                $attr[$key] = $this->generateAttr($kind, $model, $save);
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
     * @param bool   $save  Are we saving an object, or just creating an instance?
     *
     * @return string|object
     */
    public function generateAttr($kind, $model = null, $save = false)
    {
        $kind = Kind::detect($kind, $model, $save);

        return $kind->generate();
    }

    /**
     * Load the specified factories.
     *
     * @param string|string[] $paths
     *
     * @throws \League\FactoryMuffin\Exception\DirectoryNotFound
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
     * @throws \League\FactoryMuffin\Exception\DeletingFailed
     * @throws \League\FactoryMuffin\Exception\DeleteMethodNotFound
     *
     * @return void
     */
    public function deleteSaved()
    {
        $exceptions = array();
        $method = $this->deleteMethod;
        foreach ($this->saved() as $saved) {
            try {
                if (!method_exists($saved, $method)) {
                    throw new DeleteMethodNotFound($saved, $method);
                }

                $saved->$method();
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        $this->saved = array();

        if ($exceptions) {
            throw new DeletingFailed($exceptions);
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
     * Set the method we use when saving objects.
     *
     * @param string $method
     *
     * @return void
     */
    public function setSaveMethod($method)
    {
        $this->saveMethod = $method;
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
