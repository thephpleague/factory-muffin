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
 * This is the definition exception class.
 *
 * This exception is never directly thrown, but you may try to catch this
 * exception rather than the many other exceptions that extend this class.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
class DefinitionException extends Exception
{
    /**
     * The model definition name.
     *
     * @var string
     */
    private $definitionName;

    /**
     * Create a new definition exception instance.
     *
     * @param string $name    The model definition name.
     * @param string $message The exception message.
     *
     * @return void
     */
    public function __construct($name, $message)
    {
        $this->definitionName = $name;

        parent::__construct($message);
    }

    /**
     * Get the model definition name.
     *
     * @return string
     */
    public function getDefinitionName()
    {
        return $this->definitionName;
    }
}
