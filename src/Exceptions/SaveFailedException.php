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
     * The validation errors.
     *
     * @var string
     */
    private $validationErrors;

    /**
     * Create a new instance.
     *
     * @param string      $class   The model class name.
     * @param string|null $errors  The validation errors.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($class, $errors = null, $message = null)
    {
        $errors = $this->formatErrors($errors);

        $this->validationErrors = $errors;

        if (!$message) {
            if ($errors) {
                $message = "$errors We could not save the model: '$class'.";
            } else {
                $message = "We could not save the model: '$class'.";
            }
        }

        parent::__construct($class, $message);
    }

    /**
     * Format the given errors correctly.
     *
     * We're stripping any trailing whitespace, and ensuring they end in a
     * punctuation character. If null is passed, then null is returned also.
     *
     * @param string|null $errors
     *
     * @return string|null
     */
    private function formatErrors($errors)
    {
        if ($errors) {
            $errors = trim($errors);

            if (in_array(substr($errors, - 1), array('.', '!', '?'), true)) {
                return $errors;
            }

            return $errors.'.';
        }
    }

    /**
     * Get the validation errors.
     *
     * @return string
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }
}
