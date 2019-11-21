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

namespace ElementaryFramework\WaterPipe\Routing;

use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;

abstract class Route
{
    /**
     * @var string
     */
    protected $_uri = null;

    /**
     * @var Request
     */
    protected $_request;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * Route constructor.
     *
     * @param string $uri The URI handled by this Route
     *
     * @throws \Exception
     */
    public function __construct(string $uri)
    {
        $this->_request =& Request::capture();
        $this->_response = new Response();
        $this->_uri = $uri;
    }

    /**
     * Returns the URI handled by this route.
     *
     * @return string
     */
    public function getUri(): string
    {
        return $this->_uri;
    }

    /**
     * Execute an action when any kind of request method
     * is sent to this route.
     */
    public function request() {}

    /**
     * Execute an action when a GET request method
     * is sent to this route.
     */
    public function get() {}

    /**
     * Execute an action when a POST request method
     * is sent to this route.
     */
    public function post() {}

    /**
     * Execute an action when a PUT request method
     * is sent to this route.
     */
    public function put() {}

    /**
     * Execute an action when a DELETE request method
     * is sent to this route.
     */
    public function delete() {}

    /**
     * Execute an action when a HEAD request method
     * is sent to this route.
     */
    public function head() {}

    /**
     * Execute an action when a PATCH request method
     * is sent to this route.
     */
    public function patch() {}

    /**
     * Execute an action when a OPTIONS request method
     * is sent to this route.
     */
    public function options() {}
}
