<?php

use League\FactoryMuffin\Facade\FactoryMuffin;

FactoryMuffin::define('SampleModelWithValidationErrors', array());
FactoryMuffin::define('ModelCStub', array());
FactoryMuffin::define('ModelFGetKeyStub', array());
FactoryMuffin::define('ModelFPkStub', array());
FactoryMuffin::define('ModelFIdStub', array());
FactoryMuffin::define('ModelFNullStub', array());

FactoryMuffin::define('ModelWithMissingStaticMethod', array(
    'does_not_exist' => 'call|doesNotExist',
));

FactoryMuffin::define('ModelFStub', array(
    'modelGetKey' => 'factory|ModelFGetKeyStub',
    'modelPk'     => 'factory|ModelFPkStub',
    'model_id'    => 'factory|ModelFIdStub',
    'model_null'  => 'factory|ModelFNullStub',
));

FactoryMuffin::define('ModelBStub', array(
    'title'   => 'string',
    'email'   => 'email',
    'content' => 'text',
    'card'    => 'creditCardDetails',
));

FactoryMuffin::define('ModelAStub', array(
    'modelb_id'    => 'factory|ModelBStub',
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

FactoryMuffin::define('ModelDStub', array(
    'future'       => 'call|fortyDaysFromNow',
    'slug'         => 'call|makeSlug|text',
    'munged_model' => 'call|mungeModel|factory|ModelAStub',
));

FactoryMuffin::define('ModelWithStaticMethodFactory', array(
    'string' => 'just a string',
    'four'   => function () {
        return 2 + 2;
    },
));

FactoryMuffin::define('ProfileModelStub', array(
    'profile' => 'text',
));

FactoryMuffin::define('UserModelStub', array(
    'name'    => 'string',
    'active'  => 'boolean',
    'email'   => 'email',
    'profile' => 'factory|ProfileModelStub',
));
