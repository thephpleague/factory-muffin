<?php

namespace League\FactoryMuffin\Stores;

/**
 * This is the model store class.
 *
 * Useful if you want fixtures but don't actually have a persistence layer.
 *
 * @author Maarten Bicknese <maarten.bicknese@gmail.com>
 */
class InMemoryStore extends AbstractStore implements StoreInterface
{
    /**
     * Save our object to the db, and keep track of it.
     *
     * @param object $model The model instance.
     *
     * @return bool
     */
    protected function save($model)
    {
        return true;
    }

    /**
     * Delete our object from the db.
     *
     * @param object $model The model instance.
     *
     * @return mixed
     */
    protected function delete($model)
    {
        return true;
    }
}
