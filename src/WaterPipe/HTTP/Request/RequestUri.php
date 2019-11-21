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

use ElementaryFramework\WaterPipe\Exceptions\RequestUriBuilderException;

class RequestUri implements \ArrayAccess
{
    /**
     * @var string
     */
    private const URI_PARAM_PATTERN = "/:(\w+)/";

    /**
     * @var string
     */
    private $_pattern = null;

    /**
     * @var string
     */
    private $_uri = null;

    /**
     * @var array
     */
    private $_params = array();

    /**
     * @var bool
     */
    private $_built = false;

    public function __construct(string $uri = null)
    {
        if ($uri !== null)
            $this->setUri($uri);
    }

    /**
     * Checks if the this URI is equal to the given one.
     *
     * @param string $uri The URI to compare with this instance.
     *
     * @return bool
     */
    public function is(string $uri): bool
    {
        return strtolower(trim($this->_uri, "/")) === strtolower(trim($uri, "/"));
    }

    /**
     * Checks if the this URI matches the given pattern.
     *
     * @param string $pattern The Regex pattern.
     *
     * @return bool
     */
    public function match(string $pattern): bool
    {
        return static::isMatch($pattern, $this->_uri);
    }

    /**
     * @param string $pattern
     *
     * @return RequestUri
     */
    public function setPattern(string $pattern): RequestUri
    {
        $this->_pattern = $pattern;
        return $this;
    }

    /**
     * @param string $uri
     *
     * @return RequestUri
     */
    public function setUri(string $uri): RequestUri
    {
        if (preg_match("/^(http|ftp)[s]?:\/\/.+/U", $uri)) {
            $this->_uri = $uri;
        } else {
            $this->_uri = "/" . trim($uri, "/");
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getUri(): ?string
    {
        return $this->_uri;
    }

    /**
     * @return string
     */
    public function getPattern(): string
    {
        return $this->_pattern;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return $this->_params;
    }

    /**
     * @throws RequestUriBuilderException
     */
    public function build()
    {
        if ($this->_pattern !== null && $this->_uri !== null) {
            $params = self::_getUriParams($this->_pattern);

            $regex = self::pattern2regex($this->_pattern);
            preg_match("#^{$regex}\$#", $this->_uri, $values);
            array_shift($values);

            if (count($params) > 0) {
                if (count($params) < count($values)) {
                    $parts = explode("/", trim($this->_pattern, "/"));
                    $pos = array();
                    $count = 0;

                    foreach ($parts as $key => $value) {
                        if (!empty($value)) {
                            if (preg_match("#^([a-zA-Z0-9-_\.]+)$#", $value)) {
                                continue;
                            } else {
                                if (in_array(trim($value, ":"), $params, true)) {
                                    $pos[$count] = trim($value, ":");
                                } else {
                                    $pos[$count] = $count;
                                }

                                $count++;
                            }
                        }
                    }

                    $params = $pos;
                }

                $this->_params = array_combine($params, $values);
            } else {
                $this->_params = $values;
            }

            $this->_built = true;
        } else {
            $this->_built = false;

            throw new RequestUriBuilderException("Cannot build the request URI. Either the pattern or the uri are not set");
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
     * Checks if a request URI match the given pattern.
     *
     * @param string $pattern The pattern.
     * @param string $uri The request URI.
     *
     * @return bool
     */
    public static function isMatch(string $pattern, string $uri): bool
    {
        $pattern = self::pattern2regex($pattern);
        return (
            preg_match("#^{$pattern}\$#", "/" . trim($uri, "/") . "/") ||
            preg_match("#^{$pattern}\$#", "/" . trim($uri, "/")) ||
            preg_match("#^{$pattern}\$#", trim($uri, "/")) ||
            preg_match("#^{$pattern}\$#", $uri)
        ) != false;
    }

    private static function _getUriParams(string $pattern): array
    {
        $params = array(array());
        preg_match_all(self::URI_PARAM_PATTERN, $pattern, $params);

        if (isset($params[1])) {
            return $params[1];
        }

        return array();
    }

    public static function pattern2regex(string $pattern): string
    {
        return preg_replace(self::URI_PARAM_PATTERN, "([a-zA-Z0-9-_\.]+)", $pattern);
    }

    public static function makeUri(...$parts)
    {
        $uri = implode("/", $parts);

        return "/" . trim(preg_replace("#/+#", "/", $uri), "/");
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return bool true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->_params);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->_params[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->_params[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            unset($this->_params[$offset]);
        }
    }
}
