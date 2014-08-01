<?php

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\Exceptions\MethodNotFoundException;
use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * Class Call.
 *
 * @package League\FactoryMuffin\Generator
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Call extends Base
{
    /**
     * Generate, and return the attribute.
     *
     * @throws \League\FactoryMuffin\Exceptions\MethodNotFoundException
     *
     * @return mixed
     */
    public function generate()
    {
        $callable = substr($this->kind, 5);
        $params = array();

        if (strstr($callable, '|')) {
            $parts = explode('|', $callable);
            $callable = array_shift($parts);

            if ($parts[0] === 'factory' && count($parts) > 1) {
                $params[] = FactoryMuffin::create($parts[1]);
            } else {
                $attr = implode('|', $parts);
                $params[] = FactoryMuffin::generateAttr($attr, $this->object);
            }
        }

        $model = get_class($this->object);

        if (!method_exists($model, $callable)) {
            throw new MethodNotFoundException($model, $callable);
        }

        return call_user_func_array("$model::$callable", $params);
    }
}
