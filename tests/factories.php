<?php
use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelWithValidationErrors', array());
FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelC', array());
FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelGetKey', array());
FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelPk', array());
FactoryMuffin::define('League\FactoryMuffin\Test\SampleModel_id', array());
FactoryMuffin::define('League\FactoryMuffin\Test\SampleModel_null', array());

FactoryMuffin::define('League\FactoryMuffin\Test\ModelWithMissingStaticMethod', array(
  'does_not_exist' => 'call|doesNotExist'
));

FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelF', array(
    'modelGetKey' => 'factory|League\FactoryMuffin\Test\SampleModelGetKey',
    'modelPk' => 'factory|League\FactoryMuffin\Test\SampleModelPk',
    'model_id' => 'factory|League\FactoryMuffin\Test\SampleModel_id',
    'model_null' => 'factory|League\FactoryMuffin\Test\SampleModel_null',
));

FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelB', array(
    'title' => 'string',
    'email' => 'email',
    'content' => 'text',
    'card' => 'creditCardDetails'
  )
);

FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelA', array(
    'modelb_id' => 'factory|League\FactoryMuffin\Test\SampleModelB',
    'name' => 'string',
    'email' => 'email',
    'message' => 'text',
    'number' => 'integer|9',
    'created' => 'date|Y-m-d',
    'full_name' => 'name',
    'string_4' => 'string|4',
    'text_4' => 'text|4',
    'text_100' => 'text',
    'text' => 'text',
    'text_actual' => 'sneakyString',
    'boolean' => 'boolean',
    'lat' => 'latitude',
    'lon' => 'longitude',
    'text_closure' => function () {
        return 'just a string';
    },
));

FactoryMuffin::define('League\FactoryMuffin\Test\SampleModelD', array(
    'future' => 'call|fortyDaysFromNow',
    'slug' => 'call|makeSlug|text',
    'munged_model' => 'call|mungeModel|factory|League\FactoryMuffin\Test\SampleModelA'
));

FactoryMuffin::define('League\FactoryMuffin\Test\ModelWithStaticMethodFactory', array(
    'string' => 'just a string',
    'four' => function () {
        return 2 + 2;
    }
  )
);
