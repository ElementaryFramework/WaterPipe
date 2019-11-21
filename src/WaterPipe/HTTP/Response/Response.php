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

namespace ElementaryFramework\WaterPipe\HTTP\Response;

use ElementaryFramework\WaterPipe\Exceptions\FileNotFoundException;
use ElementaryFramework\WaterPipe\WaterPipe;
use ElementaryFramework\WaterPipe\WaterPipeConfig;

class Response
{
    /**
     * @var ResponseStatus
     */
    private $_status;

    /**
     * @var string
     */
    private $_body;

    /**
     * @var ResponseHeader
     */
    private $_header;

    /**
     * Response constructor.
     *
     * @throws \Exception
     */
    public function __construct()
    {
        $this->_status = new ResponseStatus(ResponseStatus::OkCode);
        $this->_header = new ResponseHeader();
        $this->_body = "";
    }

    /**
     * Sends this response to the client.
     */
    public function send()
    {
        // Execute middleware
        WaterPipe::triggerBeforeSendEvent($this);

        // Send headers
        $this->sendHeaders();

        // Send body
        $this->sendBody();

        // Exit properly
        $this->close();
    }

    public function sendHeaders() : self
    {
        // Set status code
        $code = $this->_status->getCode();
        $text = $this->_status->getDescription();

        if (strpos(PHP_SAPI, 'cgi') === 0) {
            header("Status: {$code} {$text}", true);
        } else {
            $protocol = (array_key_exists('SERVER_PROTOCOL', $_SERVER) && NULL !== $_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';
            header("{$protocol} {$code} {$text}", true, $code);
        }

        // Set headers
        foreach ($this->_header as $key => $value) {
            if ($key === "Location") {
                header("{$key}: {$value}");
            } else {
                header("{$key}: {$value}", true, $code);
            }
        }

        return $this;
    }

    public function sendBody() : self
    {
        // Print the body
        echo $this->_body;

        return $this;
    }

    /**
     * Sends an HTML string.
     *
     * @param string $body   The HTML string.
     * @param int    $status The status code.
     *
     * @throws \Exception
     */
    public function sendHtml(string $body, int $status = ResponseStatus::OkCode)
    {
        $config = WaterPipeConfig::get();

        $this->_status = new ResponseStatus($status);
        $this->_header->setContentType("text/html; charset={$config->getDefaultCharset()}");
        $this->_body = $body;

        $this->send();
    }

    /**
     * Send a JSON string.
     *
     * @param string $body   The JSON string.
     * @param int    $status The status code.
     *
     * @throws \Exception
     */
    public function sendJsonString(string $body, int $status = 200)
    {
        $config = WaterPipeConfig::get();

        $this->_status = new ResponseStatus($status);
        $this->_header->setContentType("application/json; charset={$config->getDefaultCharset()}");
        $this->_body = $body;

        $this->send();
    }

    /**
     * Send an array encoded to JSON.
     *
     * @param array $json   The JSON array.
     * @param int   $status The status code.
     *
     * @throws \Exception
     */
    public function sendJson(array $json, int $status = 200)
    {
        $this->sendJsonString(json_encode($json), $status);
    }

    /**
     * Send a raw string.
     *
     * @param string $body   The raw string.
     * @param int    $status The status code.
     *
     * @throws \Exception
     */
    public function sendText(string $body, int $status = 200)
    {
        $config = WaterPipeConfig::get();

        $this->_status = new ResponseStatus($status);
        $this->_header->setContentType("text/plain; charset={$config->getDefaultCharset()}");
        $this->_body = $body;

        $this->send();
    }

    /**
     * Send a file content.
     *
     * @param string      $path   The path to the file.
     * @param int         $status The status code.
     * @param string|null $mime   The file's MIME type.
     *
     * @throws \Exception
     * @throws FileNotFoundException When the file was not found at the given path.
     */
    public function sendFile(string $path, int $status = 200, string $mime = null)
    {
        $config = WaterPipeConfig::get();

        if (file_exists($path)) {
            $body = file_get_contents($path);

            if ($mime !== null) {
                $this->_header->setContentType("{$mime}; charset={$config->getDefaultCharset()}");
            }

            $this->_status = new ResponseStatus($status);
            $this->_body = $body;

            $this->send();
        } else {
            throw new FileNotFoundException($path);
        }
    }

    public function close()
    {
        exit(0);
    }

    /**
     * Redirect the current request to another URI
     *
     * @param string $uri The path to the new URI
     *
     * @throws \Exception
     */
    public function redirect(string $uri)
    {
        $this->_status = new ResponseStatus(ResponseStatus::PermanentRedirectCode);
        $this->_header->setLocation($uri);

        $this->sendHeaders()->close();
    }

    /**
     * Set the response body.
     *
     * @param string $body The body.
     *
     * @return self For chain calls.
     */
    public function setBody(string $body): self
    {
        $this->_body = $body;

        return $this;
    }

    /**
     * Set the response status.
     *
     * @param ResponseStatus $status The status code.
     *
     * @return self For chain calls.
     */
    public function setStatus(ResponseStatus $status): self
    {
        $this->_status = $status;

        return $this;
    }

    /**
     * Set the response header.
     *
     * @param ResponseHeader $header The response header object.
     *
     * @return self For chain calls.
     */
    public function setHeader(ResponseHeader $header): self
    {
        $this->_header = $header;

        return $this;
    }

    /**
     * Get the response body.
     *
     * @return string
     */
    public function getBody(): string
    {
        return $this->_body;
    }

    /**
     * Get the response status.
     *
     * @return ResponseStatus
     */
    public function getStatus(): ResponseStatus
    {
        return $this->_status;
    }

    /**
     * Get the response header.
     *
     * @return ResponseHeader
     */
    public function getHeader(): ResponseHeader
    {
        return $this->_header;
    }
}
