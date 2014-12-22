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

/**
 * This is the method not found exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the 2 other exceptions that extend this class.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class MethodNotFoundException extends ModelException
{
    /**
     * The method name.
     *
     * @var string
     */
    private $methodName;

    /**
     * Create a new method not found exception instance.
     *
     * @param string $class   The model class name.
     * @param string $method  The method name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $method, $message)
    {
        $this->methodName = $method;

        parent::__construct($class, $message);
    }

    /**
     * Get the method name.
     *
     * @return string
     */
    public function getMethodName()
    {
        return $this->methodName;
    }
}
