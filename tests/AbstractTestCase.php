<?php

/*
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

use League\FactoryMuffin\FactoryMuffin;
use League\FactoryMuffin\Faker\Facade as Faker;

/**
 * This is abstract test case class.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
abstract class AbstractTestCase extends PHPUnit_Framework_TestCase
{
    protected static $fm;

    public static function setupBeforeClass()
    {
        static::$fm = new FactoryMuffin();
        static::$fm->loadFactories(__DIR__.'/factories');
        Faker::setLocale('en_GB');
    }

    public static function tearDownAfterClass()
    {
        static::$fm->deleteSaved();
        static::$fm = new FactoryMuffin();
    }

    protected function reload()
    {
        static::tearDownAfterClass();
        static::setupBeforeClass();
    }
}
