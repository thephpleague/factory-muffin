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
    elseif (is_string($kind)) {
      if (substr($kind, 0, 8) == 'factory|')
      {
        return new Kind\Factory($kind, $model);
      }
      elseif (substr($kind, 0, 5) === 'call|' || substr($kind, 0, 4) === 'call')
      {
        return new Kind\Call($kind, $model);
      }
      elseif (substr($kind, 0, 5) === 'date|' || substr($kind, 0, 4) === 'date')
      {
        return new Kind\Date($kind, $model);
      }
      elseif (substr($kind, 0, 8) === 'integer|' || substr($kind, 0, 7) === 'integer')
      {
        return new Kind\Integer($kind, $model);
      }
      elseif (substr($kind, 0, 6) === 'email|' || substr($kind, 0, 5) === 'email')
      {
        return new Kind\Email($kind, $model);
      }
      elseif (substr($kind, 0, 5) === 'text|' || substr($kind, 0, 4) === 'text')
      {
        return new Kind\Text($kind, $model);
      }
      elseif (substr($kind, 0, 7) === 'string|' || substr($kind, 0, 6) === 'string')
      {
        return new Kind\String($kind, $model);
      }
      else
      {
        return new Kind\None($kind, $model);
      }
    }
  }

  public function getOption($index, $default = null)
  {
    $options = $this->getOptions();
    if (isset($options[$index])) {
      return $options[$index];
    }

    return $default;
  }

  public function getOptions()
  {
    $options = explode('|', $this->kind);
    array_shift($options);

    return $options;
  }

  abstract public function generate();
}