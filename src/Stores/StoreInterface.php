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

/**
 * This is the store interface.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
interface StoreInterface
{
    /**
     * Save the model to the database.
     *
     * @param object $model The model instance.
     *
     * @throws \League\FactoryMuffin\Exceptions\SaveFailedException
     *
     * @return void
     */
    public function persist($model);

    /**
     * Return an array of models waiting to be saved.
     *
     * @return object[]
     */
    public function pending();

    /**
     * Mark a model as waiting to be saved.
     *
     * @param object $model The model instance.
     *
     * @return void
     */
    public function markPending($model);

    /**
     * Is the model waiting to be saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isPending($model);

    /**
     * Return an array of saved models.
     *
     * @return object[]
     */
    public function saved();

    /**
     * Mark a model as saved.
     *
     * @param object $model The model instance.
     *
     * @return void
     */
    public function markSaved($model);

    /**
     * Is the model saved?
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    public function isSaved($model);

    /**
     * Delete all the saved models.
     *
     * @throws \League\FactoryMuffin\Exceptions\DeletingFailedException
     *
     * @return void
     */
    public function deleteSaved();
}
