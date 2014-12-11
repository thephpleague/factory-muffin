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

use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the factory generator class.
 *
 * The factory generator can be useful for setting up relationships between
 * models. The factory generator will return the model id of the model you ask
 * it to generate.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class FactoryGenerator implements GeneratorInterface
{
    /**
     * The kind of attribute that will be generated.
     *
     * @var string
     */
    private $kind;

    /**
     * The model instance.
     *
     * @var object
     */
    private $object;

    /**
     * The factory muffin instance.
     *
     * @var \League\FactoryMuffin\FactoryMuffin
     */
    private $factoryMuffin;

    /**
     * Create a new instance.
     *
     * @param string                              $kind          The kind of attribute.
     * @param object                              $object        The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return void
     */
    public function __construct($kind, $object, FactoryMuffin $factoryMuffin)
    {
        $this->kind = $kind;
        $this->object = $object;
        $this->factoryMuffin = $factoryMuffin;
    }

    /**
     * Generate, and return the attribute.
     *
     * @var string[]
     */
    private $methods = ['getKey', 'pk'];

    /**
     * The factory properties.
     *
     * @var string[]
     */
    private $properties = ['id', '_id'];

    /**
     * Return generated data.
     *
     * @return int|null
     */
    public function generate()
    {
        $model = substr($this->kind, 8);

        $object = $this->factory($model);

        return $this->getId($object);
    }

    /**
     * Create an instance of the model.
     *
     * This model will be automatically saved to the database if the model we
     * are generating it for has been saved (the create function was used).
     *
     * @param string $model Model class name.
     *
     * @return object
     */
    private function factory($model)
    {
        if ($this->factoryMuffin->isPendingOrSaved($this->object)) {
            return $this->factoryMuffin->create($model);
        }

        return $this->factoryMuffin->instance($model);
    }

    /**
     * Get the model id.
     *
     * @param object $object The model instance.
     *
     * @return int|null
     */
    private function getId($object)
    {
        // Check to see if we can get an id via our defined methods
        foreach ($this->methods as $method) {
            if (method_exists($object, $method)) {
                return $object->$method();
            }
        }

        // Check to see if we can get an id via our defined properties
        foreach ($this->properties as $property) {
            if (isset($object->$property)) {
                return $object->$property;
            }
        }
    }
}
