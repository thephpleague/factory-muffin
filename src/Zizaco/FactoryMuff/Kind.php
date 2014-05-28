<?php

namespace Zizaco\FactoryMuff;

abstract class Kind
{

  protected $kind = null;
  protected $model = null;

  public function __construct($kind, $model)
  {
    $this->kind = $kind;
    $this->model = $model;
  }

  public static function detect($kind, $model = null)
  {
    if($kind instanceof \Closure) {
      return new Kind\Closure($kind, $model);
    }
    elseif (is_string($kind) && substr($kind, 0, 8) == 'factory|')
    {
      return new Kind\Factory($kind, $model);
    }
    elseif (is_string($kind) && substr($kind, 0, 5) === 'call|')
    {
      return new Kind\Call($kind, $model);
    }
    elseif (is_string($kind) && substr($kind, 0, 5) === 'date|')
    {
      return new Kind\Date($kind, $model);
    }
    elseif (is_string($kind) && substr($kind, 0, 8) === 'integer|')
    {
      return new Kind\Integer($kind, $model);
    }
    else
    {
      switch ( $kind ) {
        case 'email':
          return new Kind\Email($kind, $model);
        case 'text':
          return new Kind\Text($kind, $model);
        case 'string':
          return new Kind\String($kind, $model);
        default:
          return new Kind\None($kind, $model);
      }
    }
  }

  abstract public function generate();
}