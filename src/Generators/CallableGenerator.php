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

namespace League\FactoryMuffin\Generators;

use Closure;
use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the callable generator class.
 *
 * The callable generator can be used if you want a more custom solution.
 * Whatever you return from the callable you write will be set as the attribute.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class CallableGenerator implements GeneratorInterface
{
    /**
     * The kind of attribute that will be generated.
     *
     * @var callable
     */
    protected $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    protected $object;

    /**
     * The factory muffin instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    protected $factoryMuffin;

    /**
     * Create a new instance.
     *
     * @param callable                            $kind          The kind of attribute.
     * @param object                              $object        The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return void
     */
    public function __construct(callable $kind, $object, FactoryMuffin $factoryMuffin)
    {
        if ($kind instanceof Closure) {
            $kind = $kind->bindTo($factoryMuffin);
        }

        $this->kind = $kind;
        $this->object = $object;
        $this->factoryMuffin = $factoryMuffin;
    }

    /**
     * Generate, and return the attribute.
     *
     * @return mixed
     */
    public function generate()
    {
        $saved = $this->factoryMuffin->isPendingOrSaved($this->object);

        return call_user_func($this->kind, $this->object, $saved);
    }
}
