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

namespace League\FactoryMuffin;

/**
 * This is the array utilities class.
 *
 * This class contains some array helpers we use internally.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Arr
{
    /**
     * Add an object to an array.
     *
     * @param array  $array  The array to add to.
     * @param object $object The object to add.
     *
     * @return void
     */
    public static function add(&$array, $object)
    {
        $array[spl_object_hash($object)] = $object;
    }

    /**
     * Move an object to another array.
     *
     * @param array  $old    The old array.
     * @param array  $new    The new array.
     * @param object $object The object to move.
     *
     * @return void
     */
    public static function move(&$old, &$new, $object)
    {
        $hash = spl_object_hash($object);

        unset($old[$hash]);

        $new[$hash] = $object;
    }
}
