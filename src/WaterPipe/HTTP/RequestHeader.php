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

namespace ElementaryFramework\WaterPipe\HTTP;

class RequestHeader extends Header
{
    public function setAIM(string $value)
    {
        $this->setField("A-IM", $value);
    }

    public function getAIM(): string
    {
        return $this->getField("A-IM");
    }

    public function setAccept(string $value)
    {
        $this->setField("Accept", $value);
    }

    public function getAccept(): string
    {
        return $this->getField("Accept");
    }

    public function setAcceptCharset(string $value)
    {
        $this->setField("Accept-Charset", $value);
    }

    public function getAcceptCharset(): string
    {
        return $this->getField("Accept-Charset");
    }

    public function setAcceptEncoding(string $value)
    {
        $this->setField("Accept-Encoding", $value);
    }

    public function getAcceptEncoding(): string
    {
        return $this->getField("Accept-Encoding");
    }

    public function setAcceptLanguage(string $value)
    {
        $this->setField("Accept-Language", $value);
    }

    public function getAcceptLanguage(): string
    {
        return $this->getField("Accept-Language");
    }

    public function setAcceptDatetime(string $value)
    {
        $this->setField("Accept-Datetime", $value);
    }

    public function getAcceptDatetime(): string
    {
        return $this->getField("Accept-Datetime");
    }

    public function setAccessControlRequestMethod(string $value)
    {
        $this->setField("Access-Control-Request-Method", $value);
    }

    public function getAccessControlRequestMethod(): string
    {
        return $this->getField("Access-Control-Request-Method");
    }

    public function setAuthorization(string $value)
    {
        $this->setField("Authorization", $value);
    }

    public function getAuthorization(): string
    {
        return $this->getField("Authorization");
    }

    public function setCacheControl(string $value)
    {
        $this->setField("Cache-Control", $value);
    }

    public function getCacheControl(): string
    {
        return $this->getField("Cache-Control");
    }

    public function setConnection(string $value)
    {
        $this->setField("Connection", $value);
    }

    public function getConnection(): string
    {
        return $this->getField("Connection");
    }

    public function setContentLength(int $value)
    {
        $this->setField("Content-Length", $value);
    }

    public function getContentLength(): int
    {
        return $this->getField("Content-Length");
    }

    public function setContentType(string $value)
    {
        $this->setField("Content-Type", $value);
    }

    public function getContentType(): string
    {
        return $this->getField("Content-Type");
    }

    // TODO: https://en.wikipedia.org/wiki/List_of_HTTP_header_fields
}