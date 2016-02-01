<?php
/*
 * This file is part of Factory Muffin.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 * (c) Scott Robertson <scottymeuk@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\SchemaTool;
use Doctrine\ORM\Tools\Setup;
use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;
use League\FactoryMuffin\RepositoryStore;

/**
 * This is eloquent test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DoctrineTest extends AbstractTestCase
{
    const USER_ENTITY = 'League\FactoryMuffin\Test\User';
    const CAT_ENTITY = 'League\FactoryMuffin\Test\Cat';
    /**
     * @var EntityManager
     */
    protected static $em;

    public static function setupBeforeClass()
    {
        $dbParams = [
            'driver'   => 'pdo_sqlite',
            'dbname'   => 'memory'
        ];
        $entitiesPath = [__DIR__.'/entities'];

        $config = Setup::createAnnotationMetadataConfiguration($entitiesPath, true);

        static::$em = EntityManager::create($dbParams, $config);
        static::$fm = new FactoryMuffin(new RepositoryStore(static::$em));
        $schemaTool = new SchemaTool(static::$em);
        $classes = [
            static::$em->getClassMetadata(self::USER_ENTITY),
            static::$em->getClassMetadata(self::CAT_ENTITY),
        ];
        $schemaTool->dropSchema($classes);
        $schemaTool->createSchema($classes);

        static::$fm->define(self::USER_ENTITY)->setDefinitions([
            'name'   => Faker::firstNameMale(),
            'email'  => Faker::email(),
        ]);

        static::$fm->getDefinition(self::USER_ENTITY)->setCallback(function ($model) {
            static::$em->refresh($model);
        });

        static::$fm->define(self::CAT_ENTITY)->setDefinitions([
            'name'    => Faker::firstNameFemale(),
            'user'    => 'entity|'.self::USER_ENTITY,
        ]);

        Faker::setLocale('en_GB');

        static::$fm->seed(5, self::USER_ENTITY);
        static::$fm->seed(50, self::CAT_ENTITY);
    }

    public static function tearDownAfterClass()
    {
        static::$fm->deleteSaved();
        static::$fm = new FactoryMuffin();
    }

    public function testNumberOfCats()
    {
        $cats = [];
        $users = static::$em->getRepository(self::USER_ENTITY)->findAll();

        foreach ($users as $user) {
            $userCats = $user->getCats();
            foreach ($userCats as $cat) {
                $cats[] = $cat;
            }
        }
        $this->assertCount(50, $cats);
        $this->assertInstanceOf(self::CAT_ENTITY, $cats[0]);
    }

    public function testNumberOfCatOwners()
    {
        $users = [];
        $cats = static::$em->getRepository(self::CAT_ENTITY)->findAll();
        foreach ($cats as $cat) {
            $users[] = $cat->getUser();
        }

        $this->assertCount(50, $users);
        $this->assertInstanceOf(self::USER_ENTITY, $users[0]);
    }

    public function testUserProperties()
    {
        $user = self::$em->find(self::USER_ENTITY, 1);

        $this->assertGreaterThan(1, strlen($user->getName()));
        $this->assertGreaterThan(5, strlen($user->getEmail()));
        $this->assertContains('@', $user->getEmail());
        $this->assertContains('.', $user->getEmail());
    }

    public function testCatProperties()
    {
        $cat = self::$em->find(self::CAT_ENTITY, 1);

        $this->assertGreaterThan(1, strlen($cat->getName()));
        $this->assertInstanceOf(self::USER_ENTITY, $cat->getUser());
    }

    public function testSavedObjects()
    {
        $reflection = new ReflectionClass(static::$fm);
        $store = $reflection->getProperty('modelStore');
        $store->setAccessible(true);
        $value = $store->getValue(static::$fm);

        // 50 cats + 50 corresponding users + 5 users without cats
        $this->assertCount(105, $value->saved());
        $this->assertCount(0, $value->pending());
    }

    public function testDeleteSaved()
    {
        $cat = self::$fm->create(self::CAT_ENTITY);
        $user = $cat->getUser();
        $this->assertInstanceOf(self::USER_ENTITY, $user);
        static::tearDownAfterClass();
        $users = static::$em->getRepository(self::USER_ENTITY)->findAll();
        $this->assertCount(0, $users);
    }
}
