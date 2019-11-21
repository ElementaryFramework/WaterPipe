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

namespace ElementaryFramework\WaterPipe\Routing\Middleware;

use Closure;
use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;

class MiddlewareWrapper extends Middleware
{
    /**
     * @var Closure
     */
    private $_beforeExecute;

    /**
     * @var Closure
     */
    private $_beforeSend;

    /**
     * Create a new MiddlewareWrapper
     *
     * @param callable $beforeExecute The wrapped callable for the before execute event.
     * @param callable $beforeSend    The wrapped callable for the before send event.
     */
    public function __construct(callable $beforeExecute, callable $beforeSend)
    {
        $this->_beforeExecute = Closure::fromCallable($beforeExecute);
        $this->_beforeSend = Closure::fromCallable($beforeSend);
    }

    /**
     * Executes an action just before the execution of the request.
     *
     * @param Request &$request The request which will be executed.
     *
     */
    public function beforeExecute(Request &$request)
    {
        $this->_beforeExecute->call($this, $request);
    }

    /**
     * Executes an action just before send the response.
     *
     * @param Response &$response The response which will be sent.
     */
    public function beforeSend(Response &$response)
    {
        $this->_beforeSend->call($this, $response);
    }
}
