<?php

/**
 * WaterPipe - URL routing framework for PHP
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * @category  Library
 * @package   WaterPipe
 * @author    Axel Nana <ax.lnana@outlook.com>
 * @copyright 2018 Aliens Group, Inc.
 * @license   MIT <https://github.com/ElementaryFramework/WaterPipe/blob/master/LICENSE>
 * @version   1.3.0
 * @link      http://waterpipe.na2axl.tk
 */

namespace ElementaryFramework\WaterPipe\Configuration;

/**
 * Error logger based on the `error_log` PHP function.
 */
class DefaultErrorLogger implements IErrorLogger
{
    /**
     * The message type.
     * The field only take as values the ones accepted by the
     * second parameter of the `error_log` PHP function.
     *
     * @var integer
     */
    private $_messageType;

    /**
     * The message destination.
     * The field only take as values the ones accepted by the
     * third parameter of the `error_log` PHP function.
     *
     * @var string
     */
    private $_destination;

    /**
     * The message extra headers.
     * The field is only used when message type equals 1, and
     * only take as values the ones accepted by the
     * fourth parameter of the `error_log` PHP function.
     *
     * @var string
     */
    private $_extraHeaders;

    /**
     * Creates a new instance of the default error logger.
     *
     * @param integer $messageType  The message type, serving as the second parameter of the `error_log` PHP function
     * @param string  $destination  The message destination, serving as the third parameter of the `error_log` PHP function
     * @param string  $extraHeaders The message extra headers, serving as the fourth parameter of the `error_log` PHP function
     */
    public function __construct($messageType = null, $destination = null, $extraHeaders = null)
    {
        $this->_messageType = $messageType;
        $this->_destination = $destination;
        $this->_extraHeaders = $extraHeaders;
    }

    /**
     * @inheritDoc
     */
    public function log(string $message)
    {
        error_log($message, $this->_messageType, $this->_destination, $this->_extraHeaders);
    }
}
