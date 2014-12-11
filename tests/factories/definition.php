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

$fm->define('ProfileModelStub', [
    'profile' => Faker::text(),
]);

$fm->define('NotAClass', []);

$fm->define('UserModelStub', [
    'name'    => Faker::word(),
    'active'  => Faker::boolean(),
    'email'   => Faker::email(),
    'profile' => 'factory|ProfileModelStub',
]);

$fm->define('group:UserModelStub', [
    'address' => Faker::address(),
]);

$fm->define('anothergroup:UserModelStub', [
    'address' => Faker::address(),
    'active'  => 'custom',
]);

$fm->define('callbackgroup:UserModelStub', [], function ($obj) {
    $obj->test = 'bar';
});

$fm->define('foo:DogModelStub', [
    'name' => Faker::firstNameMale(),
    'age'  => Faker::numberBetween(1, 15),
]);

$fm->define('ExampleCallbackStub', [], function ($obj, $saved) {
    $obj->callback = 'yaycalled';
    $obj->saved = $saved;
});

$fm->define('AnotherCallbackStub', [
    'foo' => Faker::email(),
], function ($obj, $saved) {
    $obj->foo = 'hello there';
    $obj->saved = $saved;
});
