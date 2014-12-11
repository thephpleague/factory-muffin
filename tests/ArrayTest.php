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

use League\FactoryMuffin\Arr;

/**
 * This is array test class.
 *
 * @group array
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class ArrayTest extends AbstractTestCase
{
    public function testGet()
    {
        $array = ['foo' => 'bar', 'baz' => 'hello'];

        $this->assertSame('bar', Arr::get($array, 'foo'));
        $this->assertSame('hello', Arr::get($array, 'baz'));
        $this->assertNull(Arr::get($array, 'bar'));
    }

    public function testHas()
    {
        $array = ['foo' => 'bar', 'baz' => 'hello'];

        $this->assertTrue(Arr::has($array, 'bar'));
        $this->assertTrue(Arr::has($array, 'hello'));
        $this->assertFalse(Arr::has($array, 'foo'));
    }
}
