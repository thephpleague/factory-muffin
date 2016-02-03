<?php

/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace League\FactoryMuffin;

use League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException;
use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\MethodNotFoundException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;

/**
 * This is the model store class.
 *
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
class RepositoryStore extends ModelStore
{
    /**
     * Methods to flush changes to storage.
     *
     * @var string
     */
    protected $flushMethod = 'flush';

    /**
     * Instance of a class that performs flushes (EntityManager for Doctrine).
     *
     * @var
     */
    protected $storage;

    public function __construct($storage)
    {
        $this->storage = $storage;
        $this->saveMethod = 'persist';
        $this->deleteMethod = 'remove';
    }

    /**
     * Save our object to the db, and keep track of it.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     * @throws \League\FactoryMuffin\Exceptions\MethodNotFoundException
     *
     * @return boolean
     */
    protected function save($model)
    {
        $method = $this->saveMethod;

        if (!method_exists($this->storage, $method)) {
            throw new SaveMethodNotFoundException(get_class($this->storage), $method);
        }

        $this->storage->$method($model);
        $this->flush();

        return true;
    }

    /**
     * Flushes changes to storage.
     *
     * @throws MethodNotFoundException
     *
     * @return boolean
     */
    protected function flush()
    {
        $method = $this->flushMethod;

        if (!method_exists($this->storage, $method)) {
            throw new MethodNotFoundException(get_class($this->storage), $method, "Can't save: flush method not found");
        }

        $this->storage->$method();

        return true;
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

        if (!method_exists($this->storage, $method)) {
            throw new DeleteMethodNotFoundException(get_class($this->storage), $method);
        }

        $this->storage->$method($model);

        return true;
    }

    /**
     * Delete all the saved models.
     *
     * @throws \League\FactoryMuffin\Exceptions\DeletingFailedException
     *
     * @return void
     */
    public function deleteSaved()
    {
        parent::deleteSaved();

        try {
            $this->storage->flush();
        } catch (\Exception $e) {
            throw new DeletingFailedException([$e]);
        }
    }
}
