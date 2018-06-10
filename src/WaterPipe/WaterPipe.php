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

namespace ElementaryFramework\WaterPipe;

use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestMethod;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestUri;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;
use ElementaryFramework\WaterPipe\Routing\Middleware\Middleware;
use ElementaryFramework\WaterPipe\Routing\Route;

class WaterPipe
{
    /**
     * Defines if the pipe is running or not.
     *
     * @var bool
     */
    private $_isRunning;

    /**
     * The array of registered middleware.
     *
     * @var Middleware[]
     */
    private $_middlewareRegistry;

    private $_getRequestRegistry;
    private $_postRequestRegistry;
    private $_putRequestRegistry;
    private $_deleteRequestRegistry;

    private $_requestRegistry;

    private $_errorsRegistry;

    public function __construct()
    {
        $this->_isRunning = false;
        $this->_middlewareRegistry = array();
        $this->_getRequestRegistry = array();
        $this->_postRequestRegistry = array();
        $this->_putRequestRegistry = array();
        $this->_deleteRequestRegistry = array();
        $this->_requestRegistry = array();
        $this->_errorsRegistry = array();
    }

    public function use(Middleware $middleware)
    {
        array_push($this->_middlewareRegistry, $middleware);
    }

    public function request(string $uri, $action)
    {
        $this->_requestRegistry[$uri] = $action;
    }

    public function get(string $uri, $action)
    {
        $this->_getRequestRegistry[$uri] = $action;
    }

    public function post(string $uri, $action)
    {
        $this->_postRequestRegistry[$uri] = $action;
    }

    public function put(string $uri, $action)
    {
        $this->_putRequestRegistry[$uri] = $action;
    }

    public function delete(string $uri, $action)
    {
        $this->_deleteRequestRegistry[$uri] = $action;
    }

    /**
     * Run the water pipe.
     *
     * This method have to be called AFTER the
     * definition of all routes. No routes will
     * be considered after the call of this method.
     *
     * @throws \Exception
     */
    public function run()
    {
        $this->exec(Request::capture());
    }

    /**
     * Execute a request with the current water pipe.
     *
     * This method have to be called AFTER the
     * definition of all routes. No routes will
     * be considered after the call of this method.
     *
     * @param Request $request The request to execute.
     *
     * @throws \Exception
     */
    public function exec(Request $request)
    {
        $this->_isRunning = true;

        $this->_executeRequest($request);
    }

    private function _executeRequest(Request $request)
    {
        $registry = null;

        switch ($request->getMethod()) {
            case RequestMethod::UNKNOWN:
                // TODO: 500 Internal Server Error. Unable to determine the request method.
                throw new \Exception("500 Error");

            case RequestMethod::GET:
                $registry = $this->_getRequestRegistry;
                break;

            case RequestMethod::POST:
                $registry = $this->_postRequestRegistry;
                break;

            case RequestMethod::PUT:
                $registry = $this->_putRequestRegistry;
                break;

            case RequestMethod::DELETE:
                $registry = $this->_deleteRequestRegistry;
                break;
        }

        if ($registry === null) {
            throw new \Exception("Cannot handle a request of this type");
        }

        $runner = $this->_getActionForRoutes($registry, $request);

        if ($runner === null) {
            $runner = $this->_getActionForRoutes($this->_requestRegistry, $request);
        }

        if ($runner === null) {
            // TODO: 404 Not Found Error. Unable to find a route for this URI.
            throw new \Exception("404 Error");
        }

        // NOTE: No code will normally not be executed after this block...
        if (is_callable($runner) || is_array($runner)) {
            call_user_func_array($runner, array($request, new Response()));
        } elseif (is_subclass_of($runner, Route::class)) {
            if (is_string($runner)) {
                $runner = new $runner($request);
            }
            $runner->execute();
        } else {
            throw new \Exception("Malformed route action");
        }
    }

    private function _getActionForRoutes(array $routes, Request $request)
    {
        $runner = null;

        foreach ($routes as $pattern => $action) {
            if (RequestUri::isMatch($pattern, $request->uri->getUri())) {
                $request->uri->setPattern($pattern)->build();
                $runner = $action;
                break;
            }
        }

        return $runner;
    }
}