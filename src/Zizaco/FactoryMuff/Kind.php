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

      $available_kinds = array(
        'call',
        'closure',
        'date',
        'email',
        'factory',
        'integer',
        'none',
        'string',
        'text',
      );

      $class = '\\Zizaco\\FactoryMuff\\Kind\\None';
      foreach ($available_kinds as $available_kind)
      {
        if (substr($kind, 0, strlen($available_kind)) === $available_kind) {
          $class = '\\Zizaco\\FactoryMuff\\Kind\\' . ucfirst($available_kind);
          break;
        }
      }

      return new $class($kind, $model);
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

    if (count($options) > 0) {
      $options = explode(',', $options[0]);
    }

    return $options;
  }

  abstract public function generate();
}