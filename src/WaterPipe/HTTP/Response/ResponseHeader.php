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

use ElementaryFramework\WaterPipe\HTTP\Header;

class ResponseHeader extends Header
{
    public function setContentType(string $value)
    {
        $this->setField("Content-Type", $value);
    }

    public function getContentType(): string
    {
        return $this->getField("Content-Type");
    }

    public function setAcceptPatch(string $value)
    {
        $this->setField("Accept-Patch", $value);
    }

    public function getAcceptPatch(): string
    {
        return $this->getField("Accept-Patch");
    }

    public function setAcceptRanges(string $value)
    {
        $this->setField("Accept-Ranges", $value);
    }

    public function getAcceptRanges(): string
    {
        return $this->getField("Accept-Ranges");
    }

    public function setAccessControlAllowCredentials(string $value)
    {
        $this->setField("Access-Control-Allow-Credentials", $value);
    }

    public function getAccessControlAllowCredentials(): string
    {
        return $this->getField("Access-Control-Allow-Credentials");
    }

    public function setAccessControlAllowHeaders(string $value)
    {
        $this->setField("Access-Control-Allow-Headers", $value);
    }

    public function getAccessControlAllowHeaders(): string
    {
        return $this->getField("Access-Control-Allow-Headers");
    }

    public function setAccessControlAllowMethods(string $value)
    {
        $this->setField("Access-Control-Allow-Methods", $value);
    }

    public function getAccessControlAllowMethods(): string
    {
        return $this->getField("Access-Control-Allow-Methods");
    }

    public function setAccessControlAllowOrigin(string $value)
    {
        $this->setField("Access-Control-Allow-Origin", $value);
    }

    public function getAccessControlAllowOrigin(): string
    {
        return $this->getField("Access-Control-Allow-Origin");
    }

    public function setAccessControlExposeHeaders(string $value)
    {
        $this->setField("Access-Control-Expose-Headers", $value);
    }

    public function getAccessControlExposeHeaders(): string
    {
        return $this->getField("Access-Control-Expose-Headers");
    }

    public function setAccessControlMaxAge(string $value)
    {
        $this->setField("Access-Control-Max-Age", $value);
    }

    public function getAccessControlMaxAge(): string
    {
        return $this->getField("Access-Control-Max-Age");
    }

    public function setAge(int $value)
    {
        $this->setField("Age", strval($value));
    }

    public function getAge(): int
    {
        return intval($this->getField("Age"));
    }

    public function setAllow(string $value)
    {
        $this->setField("Allow", $value);
    }

    public function getAllow(): string
    {
        return $this->getField("Allow");
    }

    public function setAltSvc(string $value)
    {
        $this->setField("Alt-Svc", $value);
    }

    public function getAltSvc(): string
    {
        return $this->getField("Alt-Svc");
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

    public function setContentDisposition(string $value)
    {
        $this->setField("Content-Disposition", $value);
    }

    public function getContentDisposition(): string
    {
        return $this->getField("Content-Disposition");
    }

    public function setContentEncoding(string $value)
    {
        $this->setField("Content-Encoding", $value);
    }

    public function getContentEncoding(): string
    {
        return $this->getField("Content-Encoding");
    }

    public function setContentLanguage(string $value)
    {
        $this->setField("Content-Language", $value);
    }

    public function getContentLanguage(): string
    {
        return $this->getField("Content-Language");
    }

    public function setContentLength(int $value)
    {
        $this->setField("Content-Length", strval($value));
    }

    public function getContentLength(): int
    {
        return intval($this->getField("Content-Length"));
    }

    public function setContentLocation(string $value)
    {
        $this->setField("Content-Location", $value);
    }

    public function getContentLocation(): string
    {
        return $this->getField("Content-Location");
    }

    public function setContentRange(string $value)
    {
        $this->setField("Content-Range", $value);
    }

    public function getContentRange(): string
    {
        return $this->getField("Content-Range");
    }

    public function setRefresh(string $value)
    {
        $this->setField("Refresh", $value);
    }

    public function getRefresh(): string
    {
        return $this->getField("Refresh");
    }

    public function setDate(string $value)
    {
        $this->setField("Date", $value);
    }

    public function getDate(): string
    {
        return $this->getField("Date");
    }

    public function setDeltaBase(string $value)
    {
        $this->setField("Delta-Base", $value);
    }

    public function getDeltaBase(): string
    {
        return $this->getField("Delta-Base");
    }

    public function setETag(string $value)
    {
        $this->setField("ETag", $value);
    }

    public function getETag(): string
    {
        return $this->getField("ETag");
    }

    public function setExpires(string $value)
    {
        $this->setField("Expires", $value);
    }

    public function getExpires(): string
    {
        return $this->getField("Expires");
    }

    public function setIM(string $value)
    {
        $this->setField("IM", $value);
    }

    public function getIM(): string
    {
        return $this->getField("IM");
    }

    public function setLastModified(string $value)
    {
        $this->setField("Last-Modified", $value);
    }

    public function getLastModified(): string
    {
        return $this->getField("Last-Modified");
    }

    public function setLink(string $value)
    {
        $this->setField("Link", $value);
    }

    public function getLink(): string
    {
        return $this->getField("Link");
    }

    public function setLocation(string $value)
    {
        $this->setField("Location", $value);
    }

    public function getLocation(): string
    {
        return $this->getField("Location");
    }

    public function setP3P(string $value)
    {
        $this->setField("P3P", $value);
    }

    public function getP3P(): string
    {
        return $this->getField("P3P");
    }

    public function setPragma(string $value)
    {
        $this->setField("Pragma", $value);
    }

    public function getPragma(): string
    {
        return $this->getField("Pragma");
    }

    public function setProxyAuthenticate(string $value)
    {
        $this->setField("Proxy-Authenticate", $value);
    }

    public function getProxyAuthenticate(): string
    {
        return $this->getField("Proxy-Authenticate");
    }

    public function setPublicKeyPins(string $value)
    {
        $this->setField("Public-Key-Pins", $value);
    }

    public function getPublicKeyPins(): string
    {
        return $this->getField("Public-Key-Pins");
    }

    public function setRetryAfter(string $value)
    {
        $this->setField("Retry-After", $value);
    }

    public function getRetryAfter(): string
    {
        return $this->getField("Retry-After");
    }

    public function setServer(string $value)
    {
        $this->setField("Server", $value);
    }

    public function getServer(): string
    {
        return $this->getField("Server");
    }

    public function setSetCookie(string $value)
    {
        $this->setField("Set-Cookie", $value);
    }

    public function getSetCookie(): string
    {
        return $this->getField("Set-Cookie");
    }

    public function setStrictTransportSecurity(string $value)
    {
        $this->setField("Strict-Transport-Security", $value);
    }

    public function getStrictTransportSecurity(): string
    {
        return $this->getField("Strict-Transport-Security");
    }

    public function setTrailer(string $value)
    {
        $this->setField("Trailer", $value);
    }

    public function getTrailer(): string
    {
        return $this->getField("Trailer");
    }

    public function setTransferEncoding(string $value)
    {
        $this->setField("Transfer-Encoding", $value);
    }

    public function getTransferEncoding(): string
    {
        return $this->getField("Transfer-Encoding");
    }

    public function setTk(string $value)
    {
        $this->setField("Tk", $value);
    }

    public function getTk(): string
    {
        return $this->getField("Tk");
    }

    public function setUpgrade(string $value)
    {
        $this->setField("Upgrade", $value);
    }

    public function getUpgrade(): string
    {
        return $this->getField("Upgrade");
    }

    public function setVary(string $value)
    {
        $this->setField("Vary", $value);
    }

    public function getVary(): string
    {
        return $this->getField("Vary");
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

    public function setWWWAuthenticate(string $value)
    {
        $this->setField("WWW-Authenticate", $value);
    }

    public function getWWWAuthenticate(): string
    {
        return $this->getField("WWW-Authenticate");
    }

    public function setFrameOptions(string $value)
    {
        $this->setField("X-Frame-Options", $value);
    }

    public function getFrameOptions(): string
    {
        return $this->getField("X-Frame-Options");
    }
}