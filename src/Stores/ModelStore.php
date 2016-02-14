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

use League\FactoryMuffin\Exceptions\DeleteMethodNotFoundException;
use League\FactoryMuffin\Exceptions\SaveMethodNotFoundException;

/**
 * This is the model store class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 * @author Anderson Ribeiro e Silva <dimrsilva@gmail.com>
 */
class ModelStore extends AbstractStore implements StoreInterface
{
    /**
     * Create a new model store instance.
     *
     * @param string|null $saveMethod
     * @param string|null $deleteMethod
     *
     * @return void
     */
    public function __construct($saveMethod = null, $deleteMethod = null)
    {
        $this->methods = [
            'save'   => $saveMethod ?: 'save',
            'delete' => $deleteMethod ?: 'delete',
        ];
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
        $method = $this->methods['save'];

        if (!method_exists($model, $method) || !is_callable([$model, $method])) {
            throw new SaveMethodNotFoundException(get_class($model), $method);
        }

        return $model->$method();
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
        $method = $this->methods['delete'];

        if (!method_exists($model, $method) || !is_callable([$model, $method])) {
            throw new DeleteMethodNotFoundException(get_class($model), $method);
        }

        return $model->$method();
    }
}
