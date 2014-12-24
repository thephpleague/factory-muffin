<?php

/*
 * This file is part of Factory Muffin.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace League\FactoryMuffin;

use Exception;
use League\FactoryMuffin\Exceptions\DefinitionAlreadyDefinedException;
use League\FactoryMuffin\Exceptions\DefinitionNotFoundException;
use League\FactoryMuffin\Exceptions\DeleteFailedException;
use League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException;
use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\DirectoryNotFoundException;
use League\FactoryMuffin\Exceptions\ModelNotFoundException;
use League\FactoryMuffin\Exceptions\SaveFailedException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;
use League\FactoryMuffin\Generators\GeneratorFactory;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RegexIterator;

/**
 * This is the factory muffin class.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryMuffin
{
    /**
     * The array of model definitions.
     *
     * @var \League\FactoryMuffin\Definition[]
     */
    private $definitions = [];

    /**
     * The array of models we have created and are pending save.
     *
     * @var array
     */
    private $pending = [];

    /**
     * The array of models we have created and have saved.
     *
     * @var array
     */
    private $saved = [];

    /**
     * This is the method used when saving models.
     *
     * @var string
     */
    protected $saveMethod = 'save';

    /**
     * This is the method used when deleting models.
     *
     * @var string
     */
    protected $deleteMethod = 'delete';

    /**
     * The generator factory instance.
     *
     * @var \League\FactoryMuffin\Generators\GeneratorFactory
     */
    private $generatorFactory;

    /**
     * Create a new factory muffin instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->generatorFactory = new GeneratorFactory($this);
    }

    /**
     * Set the method we use when saving models.
     *
     * @param string $method The save method name.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function setSaveMethod($method)
    {
        $this->saveMethod = $method;

        return $this;
    }

    /**
     * Set the method we use when deleting models.
     *
     * @param string $method The delete method name.
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function setDeleteMethod($method)
    {
        $this->deleteMethod = $method;

        return $this;
    }

    /**
     * Creates and saves multiple versions of a model.
     *
     * Under the hood, we're calling the create method over and over.
     *
     * @param int    $times The number of models to create.
     * @param string $name  The model definition name.
     * @param array  $attr  The model attributes.
     *
     * @return object[]
     */
    public function seed($times, $name, array $attr = [])
    {
        $seeds = [];

        for ($i = 0; $i < $times; ++$i) {
            $seeds[] = $this->create($name, $attr);
        }

        return $seeds;
    }

    /**
     * Creates and saves a model.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     *
     * @return object
     */
    public function create($name, array $attr = [])
    {
        $model = $this->make($name, $attr, true);

        $this->persist($model);

        if ($this->triggerCallback($model, $name)) {
            $this->persist($model);
        }

        return $model;
    }

    /**
     * Save the model to the database.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\SaveFailedException
     *
     * @return void
     */
    protected function persist($model)
    {
        if (!$this->save($model)) {
            if (isset($model->validationErrors) && $model->validationErrors) {
                throw new SaveFailedException(get_class($model), $model->validationErrors);
            }

            throw new SaveFailedException(get_class($model));
        }

        if (!$this->isSaved($model)) {
            Arr::move($this->pending, $this->saved, $model);
        }
    }

    /**
     * Trigger the callback if we have one.
     *
     * @param object $model The model instance.
     * @param string $name  The model definition name.
     *
     * @return bool
     */
    protected function triggerCallback($model, $name)
    {
        $callback = $this->getDefinition($name)->getCallback();

        if ($callback) {
            $saved = $this->isPendingOrSaved($model);

            return call_user_func($callback, $model, $saved) !== false;
        }

        return false;
    }

    /**
     * Make an instance of a model.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     * @param bool   $save Are we saving, or just creating an instance?
     *
     * @return object
     */
    protected function make($name, array $attr, $save)
    {
        $definition = $this->getDefinition($name);
        $model = $this->makeClass($definition->getClass(), $definition->getMaker());

        // Make the object as saved so that other generators persist correctly
        if ($save) {
            Arr::add($this->pending, $model);
        }

        // Get the attribute definitions
        $attributes = array_merge($this->getDefinition($name)->getDefinitions(), $attr);

        // Generate and save each attribute for the model
        $this->generate($model, $attributes);

        return $model;
    }

    /**
     * Make an instance of a class.
     *
     * @param string        $class The model class name.
     * @param callable|null $maker The maker callable.
     *
     * @throws \League\FactoryMuffin\Exceptions\ModelNotFoundException
     *
     * @return object
     */
    protected function makeClass($class, callable $maker = null)
    {
        if (!class_exists($class)) {
            throw new ModelNotFoundException($class);
        }

        if ($maker) {
            return call_user_func($maker, $class);
        }

        return new $class();
    }

    /**
     * Save our object to the db, and keep track of it.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     *
     * @return mixed
     */
    protected function save($model)
    {
        $method = $this->saveMethod;

        if (!method_exists($model, $method)) {
            throw new SaveMethodNotFoundException(get_class($model), $method);
        }

        return $model->$method();
    }

    /**
     * Return an array of objects to be saved.
     *
     * @return object[]
     */
    public function pending()
    {
        return $this->pending;
    }

    /**
     * Is the object going to be saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isPending($model)
    {
        return Arr::has($this->pending, $model);
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
     * Is the object saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isSaved($model)
    {
        return Arr::has($this->saved, $model);
    }

    /**
     * Is the object saved or will be saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isPendingOrSaved($model)
    {
        return $this->isSaved($model) || $this->isPending($model);
    }

    /**
     * Call the delete method on any saved objects.
     *
     * @throws \League\FactoryMuffin\Exceptions\DeletingFailedException
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function deleteSaved()
    {
        $exceptions = [];

        while ($model = array_pop($this->saved)) {
            try {
                if (!$this->delete($model)) {
                    throw new DeleteFailedException(get_class($model));
                }
            } catch (Exception $e) {
                $exceptions[] = $e;
            }
        }

        // If we ran into any problems, throw the exception now
        if ($exceptions) {
            throw new DeletingFailedException($exceptions);
        }

        return $this;
    }

    /**
     * Delete our object from the db.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException
     *
     * @return mixed
     */
    protected function delete($model)
    {
        $method = $this->deleteMethod;

        if (!method_exists($model, $method)) {
            throw new DeleteMethodNotFoundException(get_class($model), $method);
        }

        return $model->$method();
    }

    /**
     * Return an instance of the model.
     *
     * This does not save it in the database. Use create for that.
     *
     * @param string $name The model definition name.
     * @param array  $attr The model attributes.
     *
     * @return object
     */
    public function instance($name, array $attr = [])
    {
        $model = $this->make($name, $attr, false);

        $this->triggerCallback($model, $name);

        return $model;
    }

    /**
     * Generate and set the model attributes.
     *
     * @param object $model The model instance.
     * @param array  $attr  The model attributes.
     *
     * @return void
     */
    protected function generate($model, array $attr = [])
    {
        foreach ($attr as $key => $kind) {
            $model->$key = $this->generatorFactory->generate($kind, $model);
        }
    }

    /**
     * Get a model definition.
     *
     * @param string $name The model definition name.
     *
     * @throws \League\FactoryMuffin\Exceptions\DefinitionNotFoundException
     *
     * @return \League\FactoryMuffin\Definition
     */
    public function getDefinition($name)
    {
        if (!isset($this->definitions[$name])) {
            throw new DefinitionNotFoundException($name);
        }

        return $this->definitions[$name];
    }

    /**
     * Define a new model definition.
     *
     * Note that this method cannot be used to modify existing definitions.
     * Please use the getDefinition method for that.
     *
     * @param string $name The model definition name.
     *
     * @throws \League\FactoryMuffin\Exceptions\DefinitionAlreadyDefinedException
     *
     * @return \League\FactoryMuffin\Definition
     */
    public function define($name)
    {
        if (isset($this->definitions[$name])) {
            throw new DefinitionAlreadyDefinedException($name);
        }

        if (strpos($name, ':') !== false) {
            $group = current(explode(':', $name));
            $class = str_replace($group.':', '', $name);
            $this->definitions[$name] = clone $this->getDefinition($class);
            $this->definitions[$name]->setGroup($group);
        } else {
            $this->definitions[$name] = new Definition($name);
        }

        return $this->definitions[$name];
    }

    /**
     * Load the specified factories.
     *
     * This method expects either a single path to a directory containing php
     * files, or an array of directory paths, and will "require" each file.
     * These files should be where you define your model definitions.
     *
     * @param string|string[] $paths The directory path(s) to load.
     *
     * @throws \League\FactoryMuffin\Exceptions\DirectoryNotFoundException
     *
     * @return \League\FactoryMuffin\FactoryMuffin
     */
    public function loadFactories($paths)
    {
        foreach ((array) $paths as $path) {
            if (!is_dir($path)) {
                throw new DirectoryNotFoundException($path);
            }

            $this->loadDirectory($path);
        }

        return $this;
    }

    /**
     * Load all the files in a directory.
     *
     * Each required file will have this instance available as "$fm".
     *
     * @param string $path The directory path to load.
     *
     * @return void
     */
    private function loadDirectory($path)
    {
        $directory = new RecursiveDirectoryIterator($path);
        $iterator = new RecursiveIteratorIterator($directory);
        $files = new RegexIterator($iterator, '/^.+\.php$/i');

        $fm = $this;

        foreach ($files as $file) {
            require $file->getPathName();
        }
    }
}
