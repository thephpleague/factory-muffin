<?php

namespace Zizaco\FactoryMuff\Kind;

use Zizaco\FactoryMuff\Kind;

class Factory extends Kind
{
  public function generate()
  {
    $factory = new \Zizaco\FactoryMuff\FactoryMuff;
    $related = $factory->create(substr($this->kind, 8));

    if (method_exists($related, 'getKey'))
    {
      return $related->getKey();
    }
    elseif (method_exists($related, 'pk')) // Kohana Primary Key
    {
      return $related->pk();
    }
    elseif(isset($related->id)) // id Attribute
    {
      return $related->id;
    }
    elseif(isset($related->_id)) // Mongo _id attribute
    {
      return $related->_id;
    }
    else
    {
      return null;
    }
  }
}