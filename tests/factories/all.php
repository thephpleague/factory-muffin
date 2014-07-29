<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('SampleModelWithValidationErrors', array());
FactoryMuffin::define('SampleModelC', array());
FactoryMuffin::define('SampleModelGetKey', array());
FactoryMuffin::define('SampleModelPk', array());
FactoryMuffin::define('SampleModel_id', array());
FactoryMuffin::define('SampleModel_null', array());

FactoryMuffin::define('ModelWithMissingStaticMethod', array(
    'does_not_exist' => 'call|doesNotExist',
));

FactoryMuffin::define('SampleModelF', array(
    'modelGetKey' => 'factory|SampleModelGetKey',
    'modelPk'     => 'factory|SampleModelPk',
    'model_id'    => 'factory|SampleModel_id',
    'model_null'  => 'factory|SampleModel_null',
));

FactoryMuffin::define('SampleModelB', array(
    'title'   => 'string',
    'email'   => 'email',
    'content' => 'text',
    'card'    => 'creditCardDetails',
));

FactoryMuffin::define('SampleModelA', array(
    'modelb_id'    => 'factory|SampleModelB',
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

FactoryMuffin::define('SampleModelD', array(
    'future'       => 'call|fortyDaysFromNow',
    'slug'         => 'call|makeSlug|text',
    'munged_model' => 'call|mungeModel|factory|SampleModelA',
));

FactoryMuffin::define('ModelWithStaticMethodFactory', array(
    'string' => 'just a string',
    'four'   => function () {
        return 2 + 2;
    },
));

FactoryMuffin::define('ProfileStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserStub', array(
    'name'    => 'string',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileStub',
));
