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
 * This is the definition not found exception class.
 *
 * This is thrown when you try to create a model without registering its
 * definition first. This class extends DefinitionException, so you may want to
 * try to catch that exception instead, if you want to be more general.
 *
 * @author  Graham Campbell <graham@mineuk.com>
 * @author  Scott Robertson <scottymeuk@gmail.com>
 * @license <https://github.com/thephpleague/factory-muffin/blob/master/LICENSE> MIT
 */
class DefinitionNotFoundException extends DefinitionException
{
    /**
     * Create a new definition not found exception instance.
     *
     * @param string      $name    The model definition name.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($name, $message = null)
    {
        if (!$message) {
            $message = "The model definition '$name' is undefined.";
        }

        parent::__construct($name, $message);
    }
}
