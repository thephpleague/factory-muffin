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

namespace League\FactoryMuffin\Stores;

use Exception;
use League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException;
use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\FlushMethodNotFoundException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;

/**
 * This is the repository store class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Michael Bodnarchuk <davert@codeception.com>
 */
class RepositoryStore extends AbstractStore implements StoreInterface
{
    /**
     * The underlying storage instance.
     *
     * For Doctrine, this will be an EntityManager instance.
     *
     * @var object
     */
    protected $storage;

    /**
     * Create a new repository store instance.
     *
     * @param object      $storage
     * @param string|null $saveMethod
     * @param string|null $deleteMethod
     * @param string|null $deleteMethod
     *
     * @return void
     */
    public function __construct($storage, $saveMethod = null, $deleteMethod = null, $flushMethod = null)
    {
        $this->storage = $storage;

        $this->methods = [
            'save'   => $saveMethod ?: 'persist',
            'delete' => $deleteMethod ?: 'remove',
            'flush'  => $flushMethod ?: 'flush',
        ];
    }

    /**
     * Save our object to the db, and keep track of it.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\FlushMethodNotFoundException
     * @throws \League\FactoryMuffin\Exceptions\SaveMethodNotFoundException
     *
     * @return bool
     */
    protected function save($model)
    {
        $method = $this->methods['save'];

        if (!method_exists($this->storage, $method) || !is_callable([$this->storage, $method])) {
            throw new SaveMethodNotFoundException(get_class($this->storage), $method);
        }

        $this->storage->$method($model);
        $this->flush();

        return true;
    }

    /**
     * Flushes changes to storage.
     *
     * @throws \League\FactoryMuffin\Exceptions\FlushMethodNotFoundException
     *
     * @return bool
     */
    protected function flush()
    {
        $method = $this->methods['flush'];

        if (!method_exists($this->storage, $method) || !is_callable([$this->storage, $method])) {
            throw new FlushMethodNotFoundException(get_class($this->storage), $method);
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
     * @return bool
     */
    protected function delete($model)
    {
        $method = $this->methods['delete'];

        if (!method_exists($this->storage, $method) || !is_callable([$this->storage, $method])) {
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
            $this->flush();
        } catch (Exception $e) {
            throw new DeletingFailedException([$e]);
        }
    }
}
