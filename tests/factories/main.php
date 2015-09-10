<?php

use League\FactoryMuffin\Facade as FactoryMuffin;

FactoryMuffin::define('IdTestModelGetKeyStub', []);
FactoryMuffin::define('IdTestModelPkStub', []);
FactoryMuffin::define('IdTestModelIdStub', []);
FactoryMuffin::define('IdTestModelNullStub', []);

FactoryMuffin::define('ModelWithMissingStaticMethod', [
    'does_not_exist' => 'call|doesNotExist',
]);

FactoryMuffin::define('IdTestModelStub', [
    'modelGetKey' => 'factory|IdTestModelGetKeyStub',
    'modelPk'     => 'factory|IdTestModelPkStub',
    'model_id'    => 'factory|IdTestModelIdStub',
    'model_null'  => 'factory|IdTestModelNullStub',
]);

FactoryMuffin::define('FakerDefaultingModelStub', [
    'title'         => 'word',
    'email'         => 'email',
    'content'       => 'text',
    'card'          => 'creditCardDetails',
    'image'         => 'imageUrl|400;600',
    'unique_text'   => 'unique:text',
    'optional_text' => 'optional:text',
]);

FactoryMuffin::define('MainModelStub', [
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
]);

FactoryMuffin::define('ComplexModelStub', [
    'future'       => 'call|fortyDaysFromNow',
    'slug'         => 'call|makeSlug|text',
    'munged_model' => 'call|mungeModel|factory|MainModelStub',
]);

FactoryMuffin::define('ModelWithStaticMethodFactory', [
    'string' => 'just a string',
    'data'   => function ($object, $saved) {
        return compact('object', 'saved');
    },
]);
