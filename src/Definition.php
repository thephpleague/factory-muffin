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
     * The model class name.
     *
     * @var string
     */
    protected $class;

    /**
     * The model group.
     *
     * @var string|null
     */
    protected $group;

    /**
     * The maker closure.
     *
     * @var \Closure|null
     */
    protected $maker;

    /**
     * The callback closure.
     *
     * @var \Closure|null
     */
    protected $callback;

    /**
     * The attribute definitions.
     *
     * @var array
     */
    protected $definitions = [];

    /**
     * Create a new model definition instance.
     *
     * @param string $class The model class name.
     *
     * @return void
     */
    public function __construct($class)
    {
        $this->class = $class;
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
     * Set the model group.
     *
     * @param string|null $group
     *
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * Get the model group.
     *
     * @return string|null
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Set the maker closure.
     *
     * @param \Closure $maker
     *
     * @return $this
     */
    public function setMaker(Closure $maker)
    {
        $this->maker = $maker;

        return $this;
    }

    /**
     * Clear the maker closure.
     *
     * @return $this
     */
    public function clearMaker()
    {
        $this->maker = null;

        return $this;
    }

    /**
     * Get the maker closure.
     *
     * @return \Closure|null
     */
    public function getMaker()
    {
        return $this->maker;
    }

    /**
     * Set the callback closure.
     *
     * @param \Closure $callback
     *
     * @return $this
     */
    public function setCallback(Closure $callback)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * Clear the callback closure.
     *
     * @return $this
     */
    public function clearCallback()
    {
        $this->callback = null;

        return $this;
    }

    /**
     * Get the callback closure.
     *
     * @return \Closure|null
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * Add an attribute definitions.
     *
     * Note that we're appending to the original attribute definitions here.
     *
     * @param string          $attribute
     * @param string|callable $definition
     *
     * @return $this
     */
    public function addDefinition($attribute, $definition)
    {
        $this->definitions[$attribute] = $definition;

        return $this;
    }

    /**
     * Set the attribute definitions.
     *
     * Note that we're appending to the original attribute definitions here
     * instead of switching them out for the new ones.
     *
     * @param array $definitions
     *
     * @return $this
     */
    public function setDefinitions(array $definitions = [])
    {
        $this->definitions = array_merge($this->definitions, $definitions);

        return $this;
    }

    /**
     * Clear the attribute definitions.
     *
     * @return $this
     */
    public function clearDefinitions()
    {
        $this->definitions = [];

        return $this;
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
}
