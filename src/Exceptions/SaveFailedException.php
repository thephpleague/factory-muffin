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

/**
 * This is the save failed exception class.
 *
 * This is thrown when the save method of a model does not equal true in a
 * loose comparison. This class extends ModelException, so you may want to try
 * to catch that exception instead, if you want to be more general.
 *
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @author  Graham Campbell <graham@mineuk.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class SaveFailedException extends ModelException
{
    /**
     * The errors.
     *
     * @var string
     */
    private $errors;

    /**
     * Create a new instance.
     *
     * @param string      $model
     * @param string|null $errors
     * @param string|null $message
     *
     * @return void
     */
    public function __construct($model, $errors = null, $message = null)
    {
        $this->errors = $errors;

        if (!$message) {
            if ($errors) {
                $message = "$errors We could not save the model of type: '$model'.";
            } else {
                $message = "We could not save the model of type: '$model'.";
            }
        }

        parent::__construct($model, $message);
    }

    /**
     * Get the errors.
     *
     * @return string
     */
    public function getErrors()
    {
        return $this->errors;
    }
}
