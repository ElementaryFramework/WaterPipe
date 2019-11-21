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

class WaterPipeConfig
{
    /**
     * The current water pipe configuration
     * instance.
     *
     * @var WaterPipeConfig
     */
    private static $_instance = null;

    /**
     * Gets the unique instance of WaterPipeConfig.
     *
     * @return WaterPipeConfig
     */
    public static function get(): WaterPipeConfig
    {
        if (self::$_instance === null) {
            self::$_instance = new WaterPipeConfig();
        }

        return self::$_instance;
    }

    /**
     * Enables or disables the use of query strings.
     *
     * @var bool
     */
    private $_queryStringEnabled = true;

    /**
     * The default charset used for responses.
     *
     * @var string
     */
    private $_defaultCharset = "utf-8";

    /**
     * Defines if WaterPipe uses the stderr output channel
     * to print errors and uncaught exceptions.
     *
     * @var bool
     */
    private $_useStderr = true;

    /**
     * WaterPipeConfig constructor.
     */
    private function __construct()
    { }

    /**
     * Checks if query strings are enabled.
     *
     * @return bool
     */
    public function isQueryStringEnabled(): bool
    {
        return $this->_queryStringEnabled;
    }

    /**
     * Set the enabled state of query strings.
     *
     * @param bool $enable
     */
    public function setQueryStringEnabled(bool $enable): void
    {
        $this->_queryStringEnabled = $enable;
    }

    /**
     * @return string
     */
    public function getDefaultCharset(): string
    {
        return $this->_defaultCharset;
    }

    /**
     * @param string $defaultCharset
     */
    public function setDefaultCharset(string $defaultCharset): void
    {
        $this->_defaultCharset = $defaultCharset;
    }

    /**
     * Checks if WaterPipe print errors and uncaught exceptions in the stderr.
     *
     * @return  bool
     */
    public function useStderr()
    {
        return $this->_useStderr;
    }

    /**
     * Set if WaterPipe have to print errors and uncaught exceptions in the stderr.
     *
     * @param  bool $useStderr True to enable, false to disable.
     *
     * @return  self
     */
    public function setUseStderr(bool $useStderr)
    {
        $this->_useStderr = $useStderr;

        return $this;
    }
}
