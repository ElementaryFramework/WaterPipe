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
 * @version   1.4.0
 * @link      http://waterpipe.na2axl.tk
 */

namespace ElementaryFramework\WaterPipe;

use ElementaryFramework\WaterPipe\Configuration\DefaultErrorLogger;
use ElementaryFramework\WaterPipe\Configuration\IErrorLogger;
use ElementaryFramework\WaterPipe\Configuration\StdErrorLogger;

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
     * Defines the error logger to use in WaterPipe.
     *
     * @var IErrorLogger
     */
    private $_errorLogger = null;

    /**
     * WaterPipeConfig constructor.
     */
    private function __construct()
    {
        $this->_errorLogger = new DefaultErrorLogger();
    }

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
     * @param bool $enable true to enable query strings, false otherwise.
     * 
     * @return self
     */
    public function setQueryStringEnabled(bool $enable): WaterPipeConfig
    {
        $this->_queryStringEnabled = $enable;

        return $this;
    }

    /**
     * Returns the defined default charset.
     * 
     * @return string
     */
    public function getDefaultCharset(): string
    {
        return $this->_defaultCharset;
    }

    /**
     * Sets the default charset to use when sending responses.
     * 
     * @param string $defaultCharset
     * 
     * @return self
     */
    public function setDefaultCharset(string $defaultCharset): WaterPipeConfig
    {
        $this->_defaultCharset = $defaultCharset;

        return $this;
    }

    /**
     * Checks if WaterPipe print errors and uncaught exceptions in the stderr.
     * This method is deprecated and it is not recommended to use it in new projects.
     *
     * @deprecated 1.3.0
     * 
     * @return  bool
     */
    public function useStderr(): bool
    {
        return $this->_errorLogger instanceof StdErrorLogger;
    }

    /**
     * Set if WaterPipe have to print errors and uncaught exceptions in the stderr.
     * This method is deprecated and it is not recommended to use it in new projects.
     *
     * @deprecated 1.3.0
     * 
     * @param  bool $useStderr True to enable, false to disable.
     *
     * @return self
     */
    public function setUseStderr(bool $useStderr): WaterPipeConfig
    {
        $this->_errorLogger = $useStderr
            ? new StdErrorLogger()
            : new DefaultErrorLogger();

        return $this;
    }

    /**
     * Gets the error logger currently used by WaterPipe.
     *
     * @return IErrorLogger
     */
    public function errorLogger(): IErrorLogger
    {
        return $this->_errorLogger;
    }

    /**
     * Defines the error logger to use in WaterPipe.
     *
     * @param IErrorLogger $errorLogger The error logger.
     * 
     * @return self
     */
    public function setErrorLogger(IErrorLogger $errorLogger): WaterPipeConfig
    {
        $this->_errorLogger = $errorLogger;

        return $this;
    }
}
