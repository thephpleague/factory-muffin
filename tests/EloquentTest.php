<?php

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Eloquent\Model as Eloquent;
use League\FactoryMuffin\Facade as FactoryMuffin;

/**
 * @group eloquent
 */
class EloquentTest extends AbstractTestCase
{
    public static function setupBeforeClass()
    {
        $db = new DB();

        $db->addConnection(array(
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => ''
        ));

        $db->setAsGlobal();
        $db->bootEloquent();

        $db->schema()->create('users', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email');
            $table->timestamps();
        });

        $db->schema()->create('cats', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('user_id');
            $table->timestamps();
        });

        parent::setupBeforeClass();

        FactoryMuffin::seed(5, 'User');
        FactoryMuffin::seed(50, 'Cat');
    }

    public function testNumberOfCats()
    {
        $cats = array();
        foreach (User::all() as $user) {
            foreach ($user->cats as $cat)
            {
                $cats[] = $cat;
            }
        }

        $this->assertCount(50, $cats);
        $this->assertInstanceOf('Cat', $cats[0]);
    }

    public function testNumberOfCatOwners()
    {
        $users = array();
        foreach (Cat::all() as $cat) {
            $users[] = $cat->user;
        }

        $this->assertCount(50, $users);
        $this->assertCount(5, array_unique($users));
        $this->assertInstanceOf('User', $users[0]);
    }

    public function testUserProperties()
    {
        $user = User::first();

        $this->assertGreaterThan(1, strlen($user->name));
        $this->assertGreaterThan(5, strlen($user->email));
        $this->assertContains('@', $user->email);
        $this->assertContains('.', $user->email);
        $this->assertInstanceOf('DateTime', $user->created_at);
        $this->assertInstanceOf('DateTime', $user->updated_at);
        $this->assertSame((string) $user->created_at, (string) $user->updated_at);
        $this->assertFalse($user->xyz == true);
    }

    public function testCatProperties()
    {
        $cat = Cat::first();

        $this->assertGreaterThan(1, strlen($cat->name));
        $this->assertTrue($cat->user_id == true);
        $this->assertInstanceOf('DateTime', $cat->created_at);
        $this->assertInstanceOf('DateTime', $cat->updated_at);
        $this->assertSame((string) $cat->created_at, (string) $cat->updated_at);
        $this->assertFalse($cat->xyz == true);
    }
}

class User extends Eloquent
{
    public $table = 'users';

    public function cats()
    {
        return $this->hasMany('Cat');
    }
}

class Cat extends Eloquent
{
    public $table = 'cats';

    public static $name = 'cat';

    public function user()
    {
        return $this->belongsTo('User');
    }
}
