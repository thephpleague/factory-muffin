<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('IdTestModelGetKeyStub', array());
FactoryMuffin::define('IdTestModelPkStub', array());
FactoryMuffin::define('IdTestModelIdStub', array());
FactoryMuffin::define('IdTestModelNullStub', array());

FactoryMuffin::define('ModelWithMissingStaticMethod', array(
    'does_not_exist' => 'ModelWithMissingStaticMethod::doesNotExist',
));

FactoryMuffin::define('IdTestModelStub', array(
    'modelGetKey' => 'factory|IdTestModelGetKeyStub',
    'modelPk'     => 'factory|IdTestModelPkStub',
    'model_id'    => 'factory|IdTestModelIdStub',
    'model_null'  => 'factory|IdTestModelNullStub',
));

FactoryMuffin::define('FakerDefaultingModelStub', array(
    'title'         => 'word',
    'email'         => 'email',
    'content'       => 'text',
    'card'          => 'creditCardDetails',
    'image'         => 'imageUrl|400;600',
    'unique_text'   => 'unique:text',
    'optional_text' => 'optional:text',
));

FactoryMuffin::define('MainModelStub', array(
    'modelb_id'    => 'factory|FakerDefaultingModelStub',
    'name'         => 'word',
    'email'        => 'email',
    'message'      => 'text',
    'number'       => 'integer|9',
    'created'      => 'date|Y-m-d',
    'full_name'    => 'name',
    'string_4'     => 'word|4',
    'text_4'       => 'text|4',
    'text_100'     => 'text',
    'text'         => 'text',
    'text_actual'  => 'sneakyString',
    'boolean'      => 'boolean',
    'lat'          => 'latitude',
    'lon'          => 'longitude',
    'text_closure' => function () {
        return 'just a string';
    },
));

FactoryMuffin::define('ComplexModelStub', array(
    'future'       => 'ComplexModelStub::fortyDaysFromNow'
));

FactoryMuffin::define('ModelWithStaticMethodFactory', array(
    'string' => 'just a string',
    'data'   => function ($object, $saved) {
        return compact('object', 'saved');
    },
));
