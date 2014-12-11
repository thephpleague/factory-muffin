<?php

/**
 * This file is part of Factory Muffin.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

use League\FactoryMuffin\Faker\Facade as Faker;

$fm->define('ProfileModelStub')->addDefinition('profile', Faker::text());

$fm->define('AttributeDefinitionsStub');

$fm->define('NotAClass');

$fm->define('UserModelStub')->setDefinitions([
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'profile' => 'factory|ProfileModelStub',
]);

$fm->define('group:UserModelStub')->addDefinition('address', Faker::address());

$fm->define('anothergroup:UserModelStub')->setDefinitions([
    'address' => Faker::address(),
    'active'  => 'custom',
]);

$fm->define('callbackgroup:UserModelStub')->setCallback(function ($obj) {
    $obj->test = 'bar';
});

$fm->define('foo:DogModelStub')->setDefinitions([
    'name' => Faker::firstNameMale(),
    'age'  => Faker::numberBetween(1, 15),
]);

$fm->define('ExampleCallbackStub')->setCallback(function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

$fm->define('AnotherCallbackStub')->addDefinition('foo', Faker::email())->setCallback(function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
