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

namespace ElementaryFramework\WaterPipe\Routing;

use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestData;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestMethod;
use ElementaryFramework\WaterPipe\WaterPipeConfig;

class Router
{
    /**
     * @var Router
     */
    private static $_instance = null;

    /**
     * @var Request
     */
    private $_request;

    private $_built = false;

    private function __construct()
    {
        $this->_request =& Request::getInstance();
    }

    public static function &getInstance()
    {
        static $instance;

        $instance[0] = self::$_instance === null ? self::$_instance = new Router() : self::$_instance;

        return $instance[0];
    }

    public function build()
    {
        if (!$this->_built) {
            $this->_detectUri();
            $this->_detectMethod();

            $this->_built = true;
        }

        return $this;
    }

    private function _detectUri()
    {
        $config = WaterPipeConfig::get();

        if (!isset($_SERVER['REQUEST_URI']) OR !isset($_SERVER['SCRIPT_NAME'])) {
            return;
        }

        $uri = $_SERVER['REQUEST_URI'];
        if (strpos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
            $uri = substr($uri, strlen($_SERVER['SCRIPT_NAME']));
        } elseif (strpos($uri, dirname($_SERVER['SCRIPT_NAME'])) === 0) {
            $uri = substr($uri, strlen(dirname($_SERVER['SCRIPT_NAME'])));
        }

        if (strncmp($uri, '?/', 2) === 0) {
            $uri = substr($uri, 2);
        }

        $parts = preg_split('#\?#i', $uri, 2);
        $uri = $parts[0];
        if (isset($parts[1]) && $config->isQueryStringEnabled()) {
            $_SERVER['QUERY_STRING'] = $parts[1];
            parse_str($_SERVER['QUERY_STRING'], $_GET);
        } else {
            $_SERVER['QUERY_STRING'] = '';
            $_GET = array();
        }

        $this->_request->setParams(new RequestData($_GET));

        $this->_request->setBody(new RequestData($_POST));

        $this->_request->setCookies(new RequestData($_COOKIE));

        if ($uri == '/' || empty($uri)) {
            $this->_request->uri->setUri('/');
            return;
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        $this->_request->uri->setUri(str_replace(array('//', '../'), '/', trim($uri)));
    }

    private function _detectMethod()
    {
        if (isset($_SERVER["REQUEST_METHOD"])) {
            switch (strtolower($_SERVER["REQUEST_METHOD"])) {
                case "get":
                    $this->_request->setMethod(RequestMethod::GET);
                    break;

                case "post":
                    $this->_request->setMethod(RequestMethod::POST);
                    break;

                case "put":
                    $this->_request->setMethod(RequestMethod::PUT);
                    break;

                case "delete":
                    $this->_request->setMethod(RequestMethod::DELETE);
                    break;

                default:
                    $this->_request->setMethod(RequestMethod::UNKNOWN);
                    break;
            }
        } else {
            $this->_request->setMethod(RequestMethod::UNKNOWN);
        }
    }

    /**
     * @return bool
     */
    public function isBuilt(): bool
    {
        return $this->_built;
    }

    /**
     * @return Request
     */
    public function &getRequest(): Request
    {
        return $this->_request;
    }
}