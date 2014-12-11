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

namespace League\FactoryMuffin;

use Closure;

/**
 * This is the model definition class.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class Definition
{
    /**
     * The full model name.
     *
     * @var string
     */
    protected $model;

    /**
     * The model group.
     *
     * @var string|false
     */
    protected $group;

    /**
     * The model class name.
     *
     * @var string
     */
    protected $class;

    /**
     * The attribute definitions.
     *
     * @var array
     */
    protected $definitions;

    /**
     * The closure callback.
     *
     * @var \Closure|null
     */
    protected $callback;

    /**
     * Create a new model definition.
     *
     * @param string        $model       The full model name.
     * @param array         $definitions The attribute definitions.
     * @param \Closure|null $callback    The closure callback.
     *
     * @return void
     */
    public function __construct($model, array $definitions = [], Closure $callback = null)
    {
        $this->model = $model;
        $this->definitions = $definitions;
        $this->callback = $callback;

        if (strpos($model, ':') !== false) {
            $this->group = current(explode(':', $model));
            $this->class = str_replace($this->group.':', '', $model);
        } else {
            $this->class = $model;
        }
    }

    /**
     * Get the full model name including group prefixes.
     *
     * @return string
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the definition group.
     *
     * @return string|null
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Returns the real model class without the group prefix.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Get the attribute definitions.
     *
     * @return array
     */
    public function getDefinitions()
    {
        return $this->definitions;
    }

    /**
     * Get the closure callback.
     *
     * @return \Closure|null
     */
    public function getCallback()
    {
        return $this->callback;
    }
}
