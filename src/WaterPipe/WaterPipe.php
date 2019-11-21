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

namespace ElementaryFramework\WaterPipe;

use ElementaryFramework\WaterPipe\Exceptions\UnsupportedRequestMethodException;
use ElementaryFramework\WaterPipe\Exceptions\NotFoundErrorException;
use ElementaryFramework\WaterPipe\HTTP\Request\Request;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestMethod;
use ElementaryFramework\WaterPipe\HTTP\Request\RequestUri;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;
use ElementaryFramework\WaterPipe\Routing\Middleware\Middleware;
use ElementaryFramework\WaterPipe\Routing\Middleware\IRouteMiddleware;
use ElementaryFramework\WaterPipe\Routing\Middleware\MiddlewareWrapper;
use ElementaryFramework\WaterPipe\Routing\Route;
use ElementaryFramework\WaterPipe\Routing\RouteAction;
use ElementaryFramework\WaterPipe\Routing\RouteMap;

class WaterPipe
{
    /**
     * Stores the only running instance
     * of WaterPipe.
     *
     * @var WaterPipe
     */
    private static $_runningInstance = null;

    /**
     * Defines if the pipe is running or not.
     *
     * @var bool
     */
    private $_isRunning;

    /**
     * The base URI of the pipe.
     *
     * @var string
     */
    private $_baseUri = "";

    /**
     * The array of registered middleware.
     *
     * @var Middleware[]
     */
    private $_middlewareRegistry;

    /**
     * The array of registered get requests.
     *
     * @var callable[]
     */
    private $_getRequestRegistry;

    /**
     * The array of registered post requests.
     *
     * @var callable[]
     */
    private $_postRequestRegistry;

    /**
     * The array of registered put requests.
     *
     * @var callable[]
     */
    private $_putRequestRegistry;

    /**
     * The array of registered delete requests.
     *
     * @var callable[]
     */
    private $_deleteRequestRegistry;

    /**
     * The array of registered head requests.
     *
     * @var callable[]
     */
    private $_headRequestRegistry;

    /**
     * The array of registered patch requests.
     *
     * @var callable[]
     */
    private $_patchRequestRegistry;

    /**
     * The array of registered options requests.
     *
     * @var callable[]
     */
    private $_optionsRequestRegistry;

    /**
     * The array of registered requests.
     *
     * @var callable[]
     */
    private $_requestRegistry;

    /**
     * The array of registered error
     * handlers.
     *
     * @var callable[]
     */
    private $_errorsRegistry;

    /**
     * The array of sub pipes.
     *
     * @var callable[]
     */
    private $_pipesRegistry;

    /**
     * The array of route maps.
     *
     * @var RouteMap[]
     */
    private $_mapRegistry;

    /**
     * @return WaterPipe
     */
    public static function getRunningInstance(): WaterPipe
    {
        return self::$_runningInstance;
    }

    /**
     * Triggers all middlewares with before send event
     * defined on the running pipe.
     *
     * @param Response &$response The response on which trigger the event.
     */
    public static function triggerBeforeSendEvent(Response &$response)
    {
        foreach (self::$_runningInstance->_middlewareRegistry as $middleware) {
            $middleware->beforeSend($response);
        }
    }

    /**
     * Triggers all middlewares with before execute event
     * defined on the running pipe.
     *
     * @param Request &$request The request to execute.
     */
    public static function triggerBeforeExecuteEvent(Request &$request)
    {
        foreach (self::$_runningInstance->_middlewareRegistry as $middleware) {
            $middleware->beforeExecute($request);
        }
    }

    /**
     * WaterPipe __constructor
     */
    public function __construct()
    {
        $this->_isRunning = false;
        $this->_middlewareRegistry = array();
        $this->_getRequestRegistry = array();
        $this->_postRequestRegistry = array();
        $this->_putRequestRegistry = array();
        $this->_deleteRequestRegistry = array();
        $this->_headRequestRegistry = array();
        $this->_patchRequestRegistry = array();
        $this->_optionsRequestRegistry = array();
        $this->_requestRegistry = array();
        $this->_errorsRegistry = array();
        $this->_pipesRegistry = array();
        $this->_mapRegistry = array();
    }

    /**
     * Register a plugin in this pipe.
     *
     * @param MiddleWare|Route|WaterPipe $plugin The plugin to use (Middleware,
     *                                           Route or another WaterPipe).
     */
    public function use($plugin)
    {
        if ($plugin instanceof Middleware) {
            array_push($this->_middlewareRegistry, $plugin);
        }

        if ($plugin instanceof Route) {
            if ($plugin instanceof IRouteMiddleware) {
                $this->use(new MiddlewareWrapper(
                    function (Request $request) use (&$plugin) {
                        if ($request->uri->match($plugin->getUri())) {
                            $plugin->beforeExecute();
                        }
                    },
                    function (Response $response) use (&$plugin) {
                        if (Request::capture()->uri->match($plugin->getUri())) {
                            $plugin->beforeSend();
                        }
                    }
                ));
            }

            foreach (["request", "get", "post", "put", "delete", "head", "patch", "options"] as $method) {
                $this->$method($plugin->getUri(), array($plugin, $method));
            }
        }

        if ($plugin instanceof WaterPipe) {
            foreach ([
                "request" => $plugin->_requestRegistry,
                "get" => $plugin->_getRequestRegistry,
                "post" => $plugin->_postRequestRegistry,
                "put" => $plugin->_putRequestRegistry,
                "error" => $plugin->_errorsRegistry,
                "delete" => $plugin->_deleteRequestRegistry,
                "head" => $plugin->_headRequestRegistry,
                "patch" => $plugin->_patchRequestRegistry,
                "options" =>  $plugin->_optionsRequestRegistry
             ] as $method => $registry) {

                foreach ($registry as $uri => $action) {
                    $this->$method($uri, $action);
                }

            }
        }
    }

    /**
     * Maps an URI to a RouteMap.
     *
     * @param string   $baseUri  The base URI on which starts the mapping.
     * @param RouteMap $routeMap An instance of the RouteMap class defining how to map sub-routes.
     */
    public function map(string $baseUri, RouteMap $routeMap)
    {
        $this->_mapRegistry[$baseUri] = $routeMap;
    }

    /**
     * Register a request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function request(string $uri, $action)
    {
        $this->_requestRegistry[$uri] = $action;
    }

    /**
     * Register a GET request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function get(string $uri, $action)
    {
        $this->_getRequestRegistry[$uri] = $action;
    }

    /**
     * Register a POST request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function post(string $uri, $action)
    {
        $this->_postRequestRegistry[$uri] = $action;
    }

    /**
     * Register a PUT request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function put(string $uri, $action)
    {
        $this->_putRequestRegistry[$uri] = $action;
    }

    /**
     * Register a DELETE request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function delete(string $uri, $action)
    {
        $this->_deleteRequestRegistry[$uri] = $action;
    }

    /**
     * Register a HEAD request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function head(string $uri, $action)
    {
        $this->_headRequestRegistry[$uri] = $action;
    }

    /**
     * Register a PATCH request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function patch(string $uri, $action)
    {
        $this->_patchRequestRegistry[$uri] = $action;
    }

    /**
     * Register an OPTIONS request handled by this pipe.
     *
     * @param string   $uri    The request URI.
     * @param callable $action The action to call when the request
     *                         correspond to the given  URI.
     */
    public function options(string $uri, $action)
    {
        $this->_optionsRequestRegistry[$uri] = $action;
    }

    /**
     * Register a sub pipe managed by this pipe.
     *
     * @param string    $baseUri  The request URI.
     * @param WaterPipe $pipe     The pipe to run within the given base URI.
     */
    public function pipe(string $baseUri, WaterPipe $pipe)
    {
        foreach ($this->_middlewareRegistry as $middleware) {
            $pipe->use($middleware);
        }

        $this->_pipesRegistry[$baseUri] = $pipe;
    }

    /**
     * Register a error handler for this pipe.
     *
     * @param int      $code   The error code.
     * @param callable $action The action to call when an error of this code appear.
     */
    public function error(int $code, $action)
    {
        $this->_errorsRegistry[$code] = $action;
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
        $this->_isRunning = true;

        self::$_runningInstance = $this;

        Request::capture();

        $pipe = $this->_findSubPipe();

        if ($pipe === null) {
            $this->_executeRequest();
        } else {
            // Propagate errors registry
            foreach ($this->_errorsRegistry as $code => $action)
                if (!isset($pipe[1]->_errorsRegistry[$code]))
                    $pipe[1]->error($code, $action);

            // Propagate middlewares registry
            foreach ($this->_middlewareRegistry as $middleware)
                if (!in_array($middleware, $pipe[1]->_middlewareRegistry))
                    $pipe[1]->use($middleware);

            $pipe[1]->_runBase($pipe[0]);
        }
    }

    /**
     * @param string $baseUri
     * @throws \Exception
     */
    private function _runBase(string $baseUri)
    {
        $this->_baseUri = $baseUri;
        $this->run();
    }

    /**
     * @return array|null
     */
    private function _findSubPipe()
    {
        foreach ($this->_pipesRegistry as $baseUri => &$pipe) {
            if (preg_match("#^" . RequestUri::makeUri($this->_baseUri, RequestUri::pattern2regex($baseUri)) . "#", Request::capture()->uri->getUri())) {
                return array(RequestUri::makeUri($this->_baseUri, $baseUri), $pipe);
            }
        }

        return null;
    }

    /**
     * @return array|null
     */
    private function _findRouteMap()
    {
        foreach ($this->_mapRegistry as $baseUri => &$map) {
            if (preg_match("#^" . RequestUri::makeUri($this->_baseUri, RequestUri::pattern2regex($baseUri)) . "#", Request::capture()->uri->getUri())) {
                return array(RequestUri::makeUri($this->_baseUri, $baseUri), $map);
            }
        }

        return null;
    }

    /**
     * Executes the request.
     */
    private function _executeRequest()
    {
        try {
            $map = $this->_findRouteMap();

            if ($map !== null) {
                $uri = str_replace('/' . trim($map[0], "/ ") . '/', '', '/' . ltrim(Request::capture()->uri->getUri(), "/ "));
                $map[1]->map($uri);
            }

            $registry = null;

            switch (Request::capture()->getMethod()) {
                case RequestMethod::UNKNOWN:
                    if (isset($this->_errorsRegistry[500]))
                        return $this->_executeAction($this->_errorsRegistry[500]);
                    else throw new UnsupportedRequestMethodException();

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

                case RequestMethod::HEAD:
                    $registry = $this->_headRequestRegistry;
                    break;

                case RequestMethod::PATCH:
                    $registry = $this->_patchRequestRegistry;
                    break;

                case RequestMethod::OPTIONS:
                    $registry = $this->_optionsRequestRegistry;
                    break;
            }

            if ($registry === null) {
                throw new \Exception("Cannot handle a request of this type");
            }

            $runner = $this->_getActionForRoutes($registry);

            if ($runner === null) {
                $runner = $this->_getActionForRoutes($this->_requestRegistry);
            }

            if ($runner === null) {
                if (isset($this->_errorsRegistry[404]))
                    return $this->_executeAction($this->_errorsRegistry[404]);
                else throw new NotFoundErrorException();
            }

            if (!is_callable($runner) && !is_array($runner) && !is_subclass_of($runner, RouteAction::class)) {
                // TODO: Proper exception
                throw new \Exception("Malformed route action");
            }

            // Execute middleware
            self::triggerBeforeExecuteEvent(Request::capture());

            // NOTE: No code will be executed after this call...
            $this->_executeAction($runner);
        } catch (\Exception $e) {
            if (isset($this->_errorsRegistry[500])) {
                $this->_executeAction($this->_errorsRegistry[500]);
            } else {
                $config = WaterPipeConfig::get();
                if ($config->useStderr()) {
                    $stream = fopen("php://stderr", 'w');
                    $string = $e->__toString();
                    fwrite($stream, $string, strlen($string));
                    fclose($stream);
                } else throw $e;
            }
        }
    }

    private function _executeAction($runner)
    {
        if (is_callable($runner) || is_array($runner)) {
            call_user_func_array($runner, array(Request::capture(), new Response()));
        } elseif (is_subclass_of($runner, RouteAction::class)) {
            if (is_string($runner)) {
                $runner = new $runner;
            }
            $runner->execute();
        }
    }

    /**
     * @param array $routes
     * @return callable|RouteAction
     * @throws Exceptions\RequestUriBuilderException
     */
    private function _getActionForRoutes(array $routes)
    {
        $runner = null;

        foreach ($routes as $pattern => $action) {
            if (RequestUri::isMatch($pattern = RequestUri::makeUri($this->_baseUri, $pattern), Request::capture()->uri->getUri())) {
                Request::capture()->uri->setPattern($pattern)->build();
                $runner = $action;
                break;
            }
        }

        return $runner;
    }
}
