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

namespace League\FactoryMuffin\Generators;

use League\FactoryMuffin\FactoryMuffin;

/**
 * This is the generator factory class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
class GeneratorFactory
{
    /**
     * Automatically generate the attribute we want.
     *
     * @param string|callable                     $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return mixed
     */
    public function generate($kind, $model, FactoryMuffin $factoryMuffin)
    {
        $generator = $this->make($kind, $model, $factoryMuffin);

        if ($generator) {
            return $generator->generate();
        }

        return $kind;
    }

    /**
     * Automatically make the generator class we need.
     *
     * @param string|callable                     $kind          The kind of attribute.
     * @param object                              $model         The model instance.
     * @param \League\FactoryMuffin\FactoryMuffin $factoryMuffin The factory muffin instance.
     *
     * @return \League\FactoryMuffin\Generators\GeneratorInterface|null
     */
    public function make($kind, $model, FactoryMuffin $factoryMuffin)
    {
        if (is_callable($kind)) {
            return new CallableGenerator($kind, $model, $factoryMuffin);
        }

        if (is_string($kind) && strpos($kind, 'factory|') !== false) {
            return new FactoryGenerator($kind, $model, $factoryMuffin);
        }
    }
}
