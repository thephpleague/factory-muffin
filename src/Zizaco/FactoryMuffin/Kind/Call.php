<?php

namespace Zizaco\FactoryMuffin\Kind;

use Exception;
use Zizaco\FactoryMuffin\FactoryMuffin;
use Zizaco\FactoryMuffin\Kind;

class Call extends Kind
{
    public function generate()
    {
        $factory = new FactoryMuffin;
        $callable = substr($this->kind, 5);
        $params = array();

        if (strstr($callable, '|')) {
            $parts = explode('|', $callable);
            $callable = array_shift($parts);

            if ($parts[0] === 'factory' && count($parts) > 1) {
                $params[] = $factory->create($parts[1]);
            } else {
                $attr = implode('|', $parts);
                $params[] = $factory->generateAttr($attr, $this->model);
            }
        }

        if (! method_exists($this->model, $callable)) {
            throw new Exception("$this->model does not have a static $callable method");
        }

        return call_user_func_array("$this->model::$callable", $params);
    }
}
