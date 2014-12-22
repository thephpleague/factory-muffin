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
 * This is the missing model exception not found exception class.
 *
 * This is thrown when we try to create an object, but the model class defined
 * was not found. This class extends ModelException, so you may want to try to
 * catch that exception instead, if you want to be more general.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class MissingModelException extends ModelException
{
    /**
     * Create a new missing model exception instance.
     *
     * @param string      $class   The model class name.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $message = null)
    {
        if (!$message) {
            $message = "The model class '$class' is undefined.";
        }

        parent::__construct($class, $message);
    }
}
