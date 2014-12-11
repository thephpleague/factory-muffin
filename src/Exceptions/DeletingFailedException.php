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

namespace League\FactoryMuffin\Exceptions;

use Exception;

/**
 * This is the deleting failed exception class.
 *
 * This is thrown if one or more models threw an exception when we tried to
 * delete them. It's important to note that this exception will only be thrown
 * after we've attempted to delete all the saved models. You may access each
 * underlying exception, in the order they were thrown during the whole
 * process, by calling getExceptions to return an array of exceptions.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DeletingFailedException extends Exception
{
    /**
     * The array of exceptions.
     *
     * @var \Exception[]
     */
    private $exceptions;

    /**
     * Create a new instance.
     *
     * @param \Exception[] $exceptions
     * @param string|null  $message
     *
     * @return void
     */
    public function __construct(array $exceptions, $message = null)
    {
        $this->exceptions = $exceptions;

        $count = count($exceptions);

        if (!$message) {
            $message = "We encountered $count problem(s) while trying to delete the saved models.";
        }

        parent::__construct($message);
    }

    /**
     * Get the array of exceptions.
     *
     * @return \Exception[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
