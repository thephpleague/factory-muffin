<?php

namespace League\FactoryMuffin\Kind;

use Exception;
use League\FactoryMuffin\Facade\FactoryMuffin;
use League\FactoryMuffin\Kind;

/**
 * Class Call.
 *
 * @package League\FactoryMuffin\Kind
 * @author  Zizaco <zizaco@gmail.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Call extends Kind
{
    /**
     * Return generated data.
     *
     * @throws \Exception
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
                $params[] = FactoryMuffin::generateAttr($attr, $this->model);
            }
        }

        if (! method_exists($this->model, $callable)) {
            throw new Exception("$this->model does not have a static $callable method");
        }

        return call_user_func_array("$this->model::$callable", $params);
    }
}
