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
 * @version   1.1.0
 * @link      http://waterpipe.na2axl.tk
 */

namespace ElementaryFramework\WaterPipe\HTTP\Request;

use ElementaryFramework\WaterPipe\Exceptions\UnsupportedRequestMethodException;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;
use ElementaryFramework\WaterPipe\HTTP\Response\ResponseHeader;
use ElementaryFramework\WaterPipe\Routing\Router;

class Request
{
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
     * @var RequestData
     */
    private $_cookies;

    /**
     * @var RequestHeader
     */
    private $_header;

    /**
     * @var RequestUri
     */
    public $uri;

    /**
     * Request constructor.
     */
    public function __construct()
    {
        $this->uri = new RequestUri();

        $this->_header = new RequestHeader();

        foreach ($_SERVER as $key => $value) {
            if (strpos($key, "HTTP_", 0) === 0) {
                $parts = explode("_", $key);
                array_shift($parts);

                $headerName = implode("-", array_map(function ($name) {
                    return ucfirst(strtolower($name));
                }, $parts));

                $this->_header[$headerName] = $value;
            }
        }
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
     * @return RequestData|null
     */
    public function getParams(): ?RequestData
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
     * @return RequestData
     */
    public function getCookies(): RequestData
    {
        return $this->_cookies;
    }

    /**
     * @param RequestData $cookies
     */
    public function setCookies(RequestData $cookies): void
    {
        $this->_cookies = $cookies;
    }

    /**
     * @return RequestHeader
     */
    public function getHeader(): RequestHeader
    {
        return $this->_header;
    }

    /**
     * @param RequestHeader $header
     */
    public function setHeader(RequestHeader $header): void
    {
        $this->_header = $header;
    }

    /**
     * Checks if the current request is from Ajax.
     *
     * @return bool
     */
    public function isAjax(): bool
    {
        return (!empty($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) === "xmlhttprequest");
    }

    /**
     * Sends the request.
     *
     * @return bool|Response false when the request is unsuccessful, the response data otherwise.
     * @throws UnsupportedRequestMethodException
     * @throws \Exception
     */
    public function send()
    {
        $parameters = json_decode($this->getParams()->jsonSerialize(), true);

        $path = $this->uri->getUri();
        $path .= "?" . http_build_query($parameters);

        if (!($curl = @\curl_init($path)))
            return false;

        register_shutdown_function(function () use (&$curl) {
            curl_close($curl);
        });

        $headers = new ResponseHeader();

        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($_, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);

            if (count($header) < 2)
                return $len;

            $headers->setField($header[0], trim($header[1]));

            return $len;
        });

        switch ($this->_method) {
            case RequestMethod::GET:
                break;

            case RequestMethod::POST:
                \curl_setopt($curl, CURLOPT_POST, true);
                \curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($parameters));
                break;

            default:
                throw new UnsupportedRequestMethodException();
        }

        if (!($data = @\curl_exec($curl)))
            return false;

        $response = new Response();
        $response->setHeader($headers);
        $response->setBody($data);

        return $response;
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