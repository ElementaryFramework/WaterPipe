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

namespace ElementaryFramework\WaterPipe\HTTP\Request;

/**
 * Class RequestMethod
 *
 * @package  WaterPipe
 * @category Enumerations
 */
abstract class RequestMethod
{
    /**
     * Represent an unknown method.
     * @var int
     */
    public const UNKNOWN = 0;

    /**
     * The GET request method.
     * @var int
     */
    public const GET = 1;

    /**
     * The POST request method.
     * @var int
     */
    public const POST = 2;

    /**
     * The PUT request method.
     * @var int
     */
    public const PUT = 3;

    /**
     * The DELETE request method.
     * @var int
     */
    public const DELETE = 4;

    /**
     * The HEAD request method.
     * @var int
     */
    public const HEAD = 5;

    /**
     * The PATCH request method.
     * @var int
     */
    public const PATCH = 6;

    /**
     * The OPTIONS request method.
     * @var int
     */
    public const OPTIONS = 7;
}
