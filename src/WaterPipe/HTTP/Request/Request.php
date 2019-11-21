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

namespace ElementaryFramework\WaterPipe\HTTP\Request;

use ElementaryFramework\WaterPipe\Exceptions\RequestException;
use ElementaryFramework\WaterPipe\Exceptions\UnsupportedRequestMethodException;
use ElementaryFramework\WaterPipe\HTTP\Response\Response;
use ElementaryFramework\WaterPipe\HTTP\Response\ResponseHeader;
use ElementaryFramework\WaterPipe\HTTP\Response\ResponseStatus;
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
     * @var string
     */
    private $_rawBody;

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
    public function __construct(string $uri = null)
    {
        $this->uri = new RequestUri($uri);
        $this->_header = new RequestHeader();
        $this->_body = new RequestData();
        $this->_params = new RequestData();
        $this->_cookies = new RequestData();
    }

    /**
     * Returns the request method.
     *
     * @return int
     */
    public function getMethod(): int
    {
        return $this->_method;
    }

    /**
     * Sets the request method.
     *
     * @param int $type
     */
    public function setMethod(int $type): void
    {
        $this->_method = $type;
    }

    /**
     * Returns the set of request parameters from query string.
     *
     * @return RequestData|null
     */
    public function getParams(): ?RequestData
    {
        return $this->_params;
    }

    /**
     * Sets the request parameters.
     *
     * @param RequestData $params
     */
    public function setParams(RequestData $params): void
    {
        $this->_params = $params;
    }

    /**
     * Returns the request body.
     *
     * @return RequestData|string
     */
    public function getBody()
    {
        return $this->_body != null
            ? $this->_body
            : $this->_rawBody;
    }

    /**
     * Sets the request body.
     *
     * @param RequestData|string $body
     */
    public function setBody($body): void
    {
        if ($body instanceof RequestData) {
            $this->_body = $body;
            $this->_rawBody = null;
        } else {
            $this->_body = null;
            $this->_rawBody = $body;
        }
    }

    /**
     * Returns the set of cookies attached to the request.
     *
     * @return RequestData
     */
    public function getCookies(): RequestData
    {
        return $this->_cookies;
    }

    /**
     * Sets the cookies attached to the request.
     *
     * @param RequestData $cookies
     */
    public function setCookies(RequestData $cookies): void
    {
        $this->_cookies = $cookies;
    }

    /**
     * Returns the request headers.
     *
     * @return RequestHeader
     */
    public function getHeader(): RequestHeader
    {
        return $this->_header;
    }

    /**
     * Set the request headers.
     *
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
     * @return Response The response data.
     * @throws UnsupportedRequestMethodException
     * @throws RequestException When the request was not sent successfully.
     * @throws \Exception
     */
    public function send()
    {
        $parameters = $this->getParams()->toArray();
        $body = $this->getBody();

        if ($body instanceof RequestData) {
            if ($this->_header->getContentType() === "application/json") {
                $body = json_encode($body->toArray());
            } else {
                $body = http_build_query($body->toArray());
            }
        }

        $path = $this->uri->getUri();

        if (count($parameters) > 0)
            $path .= "?" . http_build_query($parameters);

        if (!($curl = @\curl_init($path)))
            throw new RequestException("Unable to initialize cURL.");

        register_shutdown_function(function () use (&$curl) {
            \curl_close($curl);
        });

        $headers = new ResponseHeader();

        \curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        \curl_setopt($curl, CURLOPT_HEADERFUNCTION, function ($_, $header) use (&$headers) {
            $len = strlen($header);
            $header = explode(':', $header, 2);

            if (count($header) >= 2)
                $headers->setField(trim($header[0]), trim($header[1]));

            return $len;
        });

        switch ($this->_method) {
            case RequestMethod::GET:
                break;

            case RequestMethod::POST:
                \curl_setopt($curl, CURLOPT_POST, true);
                \curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;

            case RequestMethod::DELETE:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                \curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;

            case RequestMethod::PUT:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                \curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;

            case RequestMethod::PATCH:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
                \curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
                break;

            case RequestMethod::HEAD:
                \curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "HEAD");
                break;

            default:
                throw new UnsupportedRequestMethodException();
        }

        \curl_setopt($curl, CURLOPT_HEADER, true);
        \curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeader()->toArray());

        if (!($data = @\curl_exec($curl)))
            throw new RequestException(\curl_error($curl));

        $data = substr($data, \curl_getinfo($curl, CURLINFO_HEADER_SIZE));

        $response = new Response();
        $response->setHeader($headers);
        $response->setBody($data);
        $response->setStatus(new ResponseStatus(intval(\curl_getinfo($curl, CURLINFO_HTTP_CODE))));

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
