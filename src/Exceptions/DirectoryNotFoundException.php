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
 * This is the directory not found exception class.
 *
 * This is thrown if you try to load model definitions from a directory that
 * doesn't exit.
 *
 * @author Graham Campbell <graham@mineuk.com>
 * @author Scott Robertson <scottymeuk@gmail.com>
 */
class DirectoryNotFoundException extends Exception
{
    /**
     * The path.
     *
     * @var string
     */
    private $path;

    /**
     * Create a new directory not found exception instance.
     *
     * @param string      $path    The directory path.
     * @param string|null $message The exception message.
     *
     * @return void
     */
    public function __construct($path, $message = null)
    {
        $this->path = $path;

        if (!$message) {
            $message = "The directory '$path' was not found.";
        }

        parent::__construct($message);
    }

    /**
     * Get the path.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
