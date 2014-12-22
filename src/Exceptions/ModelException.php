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

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the model exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the many other exceptions that extend this class.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class ModelException extends Exception
{
    /**
     * The model.
     *
     * @var string
     */
    private $modelClass;

    /**
     * Create a new model exception instance.
     *
     * @param string $class   The model class name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $message)
    {
        $this->modelClass = $class;

        parent::__construct($message);
    }

    /**
     * Get the model class name.
     *
     * @return string
     */
    public function getModelClass()
    {
        return $this->modelClass;
    }
}
