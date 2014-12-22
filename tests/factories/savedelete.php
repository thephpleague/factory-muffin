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

$fm->define('ModelThatWillSaveStub');
$fm->define('ModelThatFailsToSaveStub');
$fm->define('ModelThatFailsToDeleteStub');
$fm->define('ModelThatAlsoFailsToDeleteStub');
$fm->define('ModelWithNoSaveMethodStub');
$fm->define('ModelWithNoDeleteMethodStub');
$fm->define('ModelWithValidationErrorsStub');
$fm->define('ModelWithBadValidationErrorsStub');
$fm->define('ModelWithTrackedSaves');

$fm->define('no return:ModelWithTrackedSaves')->setCallback(function () {
    // No return is treated as true
});

$fm->define('return true:ModelWithTrackedSaves')->setCallback(function () {
    return true;
});

$fm->define('return false:ModelWithTrackedSaves')->setCallback(function () {
    return false;
});
