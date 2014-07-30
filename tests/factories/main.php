<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('IdTestModelGetKeyStub', array());
FactoryMuffin::define('IdTestModelPkStub', array());
FactoryMuffin::define('IdTestModelIdStub', array());
FactoryMuffin::define('IdTestModelNullStub', array());

FactoryMuffin::define('ModelWithMissingStaticMethod', array(
    'does_not_exist' => 'call|doesNotExist',
));

FactoryMuffin::define('IdTestModelStub', array(
    'modelGetKey' => 'factory|IdTestModelGetKeyStub',
    'modelPk'     => 'factory|IdTestModelPkStub',
    'model_id'    => 'factory|IdTestModelIdStub',
    'model_null'  => 'factory|IdTestModelNullStub',
));

FactoryMuffin::define('FakerDefaultingModelStub', array(
    'title'   => 'string',
    'email'   => 'email',
    'content' => 'text',
    'card'    => 'creditCardDetails',
    'image' => 'imageUrl|400;600'
));

FactoryMuffin::define('MainModelStub', array(
    'modelb_id'    => 'factory|FakerDefaultingModelStub',
    'name'         => 'string',
    'email'        => 'email',
    'message'      => 'text',
    'number'       => 'integer|9',
    'created'      => 'date|Y-m-d',
    'full_name'    => 'name',
    'string_4'     => 'string|4',
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
    'future'       => 'call|fortyDaysFromNow',
    'slug'         => 'call|makeSlug|text',
    'munged_model' => 'call|mungeModel|factory|MainModelStub',
));

FactoryMuffin::define('ModelWithStaticMethodFactory', array(
    'string' => 'just a string',
    'four'   => function () {
        return 2 + 2;
    },
));
