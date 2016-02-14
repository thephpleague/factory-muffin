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
use League\FactoryMuffin\Exceptions\DeleteFailedException;
use League\FactoryMuffin\Exceptions\DeletingFailedException;
use League\FactoryMuffin\Exceptions\SaveFailedException;

/**
 * This is the model store class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Anderson Ribeiro e Silva <dimrsilva@gmail.com>
 */
abstract class AbstractStore
{
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
     * The underlying operational method names.
     *
     * @var string[]
     */
    protected $methods = [];

    /**
     * Save the model to the database.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\SaveFailedException
     *
     * @return void
     */
    public function persist($model)
    {
        if (!$this->save($model)) {
            if (isset($model->validationErrors) && $model->validationErrors) {
                throw new SaveFailedException(get_class($model), $model->validationErrors);
            }

            throw new SaveFailedException(get_class($model));
        }

        if (!$this->isSaved($model)) {
            $this->markSaved($model);
        }
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
    abstract protected function save($model);

    /**
     * Return an array of models waiting to be saved.
     *
     * @return object[]
     */
    public function pending()
    {
        return $this->pending;
    }

    /**
     * Mark a model as waiting to be saved.
     *
     * @param object $model The model instance.
     *
     * @return void
     */
    public function markPending($model)
    {
        $hash = spl_object_hash($model);

        $this->pending[$hash] = $model;
    }

    /**
     * Is the model waiting to be saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isPending($model)
    {
        return in_array($model, $this->pending, true);
    }

    /**
     * Return an array of saved models.
     *
     * @return object[]
     */
    public function saved()
    {
        return $this->saved;
    }

    /**
     * Mark a model as saved.
     *
     * @param object $model The model instance.
     *
     * @return void
     */
    public function markSaved($model)
    {
        $hash = spl_object_hash($model);

        if (isset($this->pending[$hash])) {
            unset($this->pending[$hash]);
        }

        $this->saved[$hash] = $model;
    }

    /**
     * Is the model saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isSaved($model)
    {
        return in_array($model, $this->saved, true);
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
    abstract protected function delete($model);
}
