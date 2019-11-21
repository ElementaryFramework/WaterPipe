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

use ElementaryFramework\WaterPipe\HTTP\Header;

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

    public function setAccessControlRequestHeaders(string $value)
    {
        $this->setField("Access-Control-Request-Headers", $value);
    }

    public function getAccessControlRequestHeaders(): string
    {
        return $this->getField("Access-Control-Request-Headers");
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
        $this->setField("Content-Length", strval($value));
    }

    public function getContentLength(): int
    {
        return intval($this->getField("Content-Length"));
    }

    public function setContentType(string $value)
    {
        $this->setField("Content-Type", $value);
    }

    public function getContentType(): string
    {
        return $this->getField("Content-Type");
    }

    public function setDate(string $value)
    {
        $this->setField("Date", $value);
    }

    public function getDate(): string
    {
        return $this->getField("Date");
    }

    public function setExpect(string $value)
    {
        $this->setField("Expect", $value);
    }

    public function getExpect(): string
    {
        return $this->getField("Expect");
    }

    public function setForwarded(string $value)
    {
        $this->setField("Forwarded", $value);
    }

    public function getForwarded(): string
    {
        return $this->getField("Forwarded");
    }

    public function setFrom(string $value)
    {
        $this->setField("From", $value);
    }

    public function getFrom(): string
    {
        return $this->getField("From");
    }

    public function setHost(string $value)
    {
        $this->setField("Host", $value);
    }

    public function getHost(): string
    {
        return $this->getField("Host");
    }

    public function setIfMatch(string $value)
    {
        $this->setField("If-Match", $value);
    }

    public function getIfMatch(): string
    {
        return $this->getField("If-Match");
    }

    public function setIfModifiedSince(string $value)
    {
        $this->setField("If-Modified-Since", $value);
    }

    public function getIfModifiedSince(): string
    {
        return $this->getField("If-Modified-Since");
    }

    public function setIfNoneMatch(string $value)
    {
        $this->setField("If-None-Match", $value);
    }

    public function getIfNoneMatch(): string
    {
        return $this->getField("If-None-Match");
    }

    public function setIfRange(string $value)
    {
        $this->setField("If-Range", $value);
    }

    public function getIfRange(): string
    {
        return $this->getField("If-Range");
    }

    public function setIfUnmodifiedSince(string $value)
    {
        $this->setField("If-Unmodified-Since", $value);
    }

    public function getIfUnmodifiedSince(): string
    {
        return $this->getField("If-Unmodified-Since");
    }

    public function setMaxForward(int $value)
    {
        $this->setField("Max-Forward", strval($value));
    }

    public function getMaxForward(): int
    {
        return intval($this->getField("Max-Forward"));
    }

    public function setOrigin(string $value)
    {
        $this->setField("Origin", $value);
    }

    public function getOrigin(): string
    {
        return $this->getField("Origin");
    }

    public function setPragma(string $value)
    {
        $this->setField("Pragma", $value);
    }

    public function getPragma(): string
    {
        return $this->getField("Pragma");
    }

    public function setProxyAuthorization(string $value)
    {
        $this->setField("Proxy-Authorization", $value);
    }

    public function getProxyAuthorization(): string
    {
        return $this->getField("Proxy-Authorization");
    }

    public function setRange(string $value)
    {
        $this->setField("Range", $value);
    }

    public function getRange(): string
    {
        return $this->getField("Range");
    }

    public function setRefer(string $value)
    {
        $this->setField("Refer", $value);
    }

    public function getRefer(): string
    {
        return $this->getField("Refer");
    }

    public function setTE(string $value)
    {
        $this->setField("TE", $value);
    }

    public function getTE(): string
    {
        return $this->getField("TE");
    }

    public function setUserAgent(string $value)
    {
        $this->setField("User-Agent", $value);
    }

    public function getUsrAgent(): string
    {
        return $this->getField("User-Agent");
    }

    public function setUpgrade(string $value)
    {
        $this->setField("Upgrade", $value);
    }

    public function getUpdate(): string
    {
        return $this->getField("Upgrade");
    }

    public function setVia(string $value)
    {
        $this->setField("Via", $value);
    }

    public function getVia(): string
    {
        return $this->getField("Via");
    }

    public function setWarning(string $value)
    {
        $this->setField("Warning", $value);
    }

    public function getWarning(): string
    {
        return $this->getField("Warning");
    }

    public function setRequestedWith(string $value)
    {
        $this->setField("X-Requested-With", $value);
    }

    public function getRequestedWith(): string
    {
        return $this->getField("X-Requested-With");
    }

    public function setCsrfToken(string $value)
    {
        $this->setField("X-Csrf-Token", $value);
    }

    public function getCsrfToken(): string
    {
        return $this->getField("X-Csrf-Token");
    }
}