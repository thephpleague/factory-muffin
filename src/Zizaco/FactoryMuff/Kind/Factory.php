<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Factory extends Kind
{
  private $methods = array(
    'getKey',
    'pk',
  );

  private $properties = array(
    'id',
    '_id'
  );

  public function generate()
  {
    $factory = new \Zizaco\FactoryMuff\FactoryMuff;
    $model = $factory->create(substr($this->kind, 8));
    return $this->getId($model);
  }

  private function getId($model)
  {
    // Check to see if we can get an ID via our defined methods
    foreach ($this->methods as $method) {
      if (method_exists($model, $method)) {
        return $model->$method();
      }
    }

    // Check to see if we can get an ID via our defined methods
    foreach ($this->properties as $property) {
      if (isset($model->$property)) {
        return $model->$property;
      }
    }

    // We cannot find an ID
    return null;
  }
}
