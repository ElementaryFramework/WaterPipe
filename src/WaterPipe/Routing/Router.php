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
use ElementaryFramework\WaterPipe\HTTP\Request\RequestData;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestHeader;
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

    /**
     * @var bool
     */
    private $_built = false;

    /**
     * Router constructor
     */
    private function __construct()
    {
        $this->_request = new Request();
    }

    /**
     * Returns the unique instance of the Router.
     *
     * @return self
     */
    public static function &getInstance()
    {
        static $instance;

        $instance[0] = self::$_instance === null ? self::$_instance = new Router() : self::$_instance;

        return $instance[0];
    }

    /**
     * Builds the router and capture the request.
     *
     * @return self
     */
    public function build(): self
    {
        if (!$this->_built) {
            $this->_detectHeaders();
            $this->_detectMethod();
            $this->_detectUri();

            $this->_built = true;
        }

        return $this;
    }

    /**
     * Detects the current request method.
     */
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

                case "head":
                    $this->_request->setMethod(RequestMethod::HEAD);
                    break;

                case "patch":
                    $this->_request->setMethod(RequestMethod::PATCH);
                    break;

                case "options":
                    $this->_request->setMethod(RequestMethod::OPTIONS);
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
     * Detects the current request URI.
     */
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

        $data = new RequestData();

        $contentType = trim(explode(";", $this->_request->getHeader()->getContentType())[0]);

        if (count($_POST) > 0) {
            $data = $_POST;
        } elseif ($this->_request->getMethod() === RequestMethod::PUT || $this->_request->getMethod() === RequestMethod::PATCH) {
            $handle = fopen("php://input", "r");
            $rawData = '';
            while ($chunk = fread($handle, 1024)) $rawData .= $chunk;
            fclose($handle);

            switch ($contentType) {
                case "application/json":
                    $data = new RequestData(json_decode($rawData, true));
                    break;
                case "application/xml":
                    $data = new RequestData((array)simplexml_load_string($rawData));
                    break;
                case "application/x-www-form-urlencoded":
                    parse_str($rawData, $data);
                    $data = new RequestData($data);
                    break;
                default:
                    $data = $rawData;
                    break;
            }
        } elseif ($this->_request->getMethod() !== RequestMethod::GET) {
            $rawData = file_get_contents("php://input");

            switch ($contentType) {
                case "application/json":
                    $data = new RequestData(json_decode($rawData, true));
                    break;
                case "application/xml":
                    $data = new RequestData((array)simplexml_load_string($rawData));
                    break;
                case "application/x-www-form-urlencoded":
                    parse_str($rawData, $data);
                    $data = new RequestData($data);
                    break;
                default:
                    $data = $rawData;
                    break;
            }
        }

        $this->_request->setBody($data);

        $this->_request->setCookies(new RequestData($_COOKIE));

        if ($uri == '/' || empty($uri)) {
            $this->_request->uri->setUri('/');
            return;
        }

        $uri = parse_url($uri, PHP_URL_PATH);

        $this->_request->uri->setUri(str_replace(array('//', '../'), '/', trim($uri)));
    }

    /**
     * Detects the current request headers.
     */
    private function _detectHeaders()
    {
        if (is_array($headers = $this->_getallheaders())) {
            $header = new RequestHeader();

            foreach ($headers as $key => $value) {
                $header->setField($key, $value);
            }

            $this->_request->setHeader($header);
        }
    }

    private function _getallheaders()
    {
        if (!function_exists("getallheaders")) {
            $headers = [];
            foreach ($_SERVER as $name => $value)
                if (substr($name, 0, 5) == 'HTTP_')
                    $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;

            return $headers;
        } else {
            return getallheaders();
        }
    }

    /**
     * Checks if the router is built or not.
     *
     * @return bool
     */
    public function isBuilt(): bool
    {
        return $this->_built;
    }

    /**
     * Returns the captured request at the build time
     * of the router.
     *
     * @return Request
     */
    public function &getRequest(): Request
    {
        return $this->_request;
    }
}
