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
 * @version   0.0.1
 * @link      http://waterpipe.na2axl.tk
 */

namespace ElementaryFramework\WaterPipe\HTTP\Request;

use ElementaryFramework\WaterPipe\Routing\Router;

class Request
{
    /**
     * @var Request
     */
    private static $_instance = null;

    /**
     * @var int
     */
    private $_method;

    /**
     * @var RequestData
     */
    private $_params;

    /**
     * @var RequestData
     */
    private $_body;

    /**
     * @var RequestUri
     */
    public $uri;

    /**
     * Request constructor.
     */
    private function __construct()
    {
        $this->uri = new RequestUri();
    }

    /**
     * @return Request
     */
    public static function &getInstance(): Request
    {
        static $instance;

        $instance[0] = self::$_instance === null ? self::$_instance = new Request() : self::$_instance;

        return $instance[0];
    }

    /**
     * @return int
     */
    public function getMethod(): int
    {
        return $this->_method;
    }

    /**
     * @param int $type
     */
    public function setMethod(int $type): void
    {
        $this->_method = $type;
    }

    /**
     * @return RequestData
     */
    public function getParams(): RequestData
    {
        return $this->_params;
    }

    /**
     * @param RequestData $params
     */
    public function setParams(RequestData $params): void
    {
        $this->_params = $params;
    }

    /**
     * @return RequestData
     */
    public function getBody(): RequestData
    {
        return $this->_body;
    }

    /**
     * @param RequestData $body
     */
    public function setBody(RequestData $body): void
    {
        $this->_body = $body;
    }

    /**
     * Captures the current request.
     *
     * @return Request
     */
    public static function &capture(): Request
    {
        return Router::getInstance()->build()->getRequest();
    }
}