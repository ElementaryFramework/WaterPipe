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

/**
 * Request Data
 *
 * Stores the set of parameters bundled with a request.
 *
 * @author     Axel Nana <ax.lnana@outlook.com>
 */
class RequestData implements \ArrayAccess, \Countable, \JsonSerializable, \Serializable, \SeekableIterator
{
    /**
     * The array of parameters.
     *
     * @var array
     */
    private $_parameters;

    /**
     * The position of the current iterator.
     *
     * @var int
     */
    private $_position = 0;

    /**
     * RequestData constructor.
     *
     * @param array $params The array of parameters.
     */
    public function __construct(array $params = array())
    {
        $this->_parameters = $params;
    }

    /**
     * Whether a offset exists.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset An offset to check for.
     *
     * @return bool true on success or false on failure. The return value will
     *              be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset): bool
    {
        return array_key_exists($offset, $this->_parameters);
    }

    /**
     * Offset to retrieve.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset The offset to retrieve.
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->_parameters[$offset];
        }

        return null;
    }

    /**
     * Offset to set.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     *
     * @return void
     */
    public function offsetSet($offset, $value): void
    {
        if ($this->offsetExists($offset)) {
            $this->_parameters[$offset] = $value;
        }
    }

    /**
     * Offset to unset.
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset The offset to unset.
     *
     * @return void
     */
    public function offsetUnset($offset): void
    {
        if ($this->offsetExists($offset)) {
            unset($this->_parameters[$offset]);
        }
    }

    /**
     * Return the current element.
     *
     * @link http://php.net/manual/en/iterator.current.php
     *
     * @return mixed
     */
    public function current()
    {
        $values = array_values($this->_parameters);

        return $values[$this->_position];
    }

    /**
     * Move forward to next element.
     *
     * @link http://php.net/manual/en/iterator.next.php
     *
     * @return void
     */
    public function next(): void
    {
        $this->_position++;
    }

    /**
     * Return the key of the current element.
     *
     * @link http://php.net/manual/en/iterator.key.php
     *
     * @return mixed Scalar on success, or null on failure.
     */
    public function key()
    {
        $keys = array_keys($this->_parameters);

        return $keys[$this->_position];
    }

    /**
     * Checks if current position is valid.
     *
     * @link http://php.net/manual/en/iterator.valid.php
     *
     * @return bool The return value will be casted to boolean and then evaluated.
     *              Returns true on success or false on failure.
     */
    public function valid(): bool
    {
        return $this->_position < count($this->_parameters);
    }

    /**
     * Rewind the Iterator to the first element.
     *
     * @link http://php.net/manual/en/iterator.rewind.php
     *
     * @return void
     */
    public function rewind(): void
    {
        $this->_position = 0;
    }

    /**
     * Seeks to a position.
     *
     * @link http://php.net/manual/en/seekableiterator.seek.php
     *
     * @param int $position The position to seek to.
     *
     * @return void
     */
    public function seek($position): void
    {
        $this->_position = $position;
    }

    /**
     * String representation of object.
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     *
     * @return string The string representation of the object or null
     */
    public function serialize(): string
    {
        return $this->jsonSerialize();
    }

    /**
     * Constructs the object.
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     *
     * @param string $serialized The string representation of the object.
     *
     * @return void
     */
    public function unserialize($serialized): void
    {
        $this->_parameters = json_decode($serialized, true);
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed Data which can be serialized by json_encode, which is a
     *               value of any type other than a resource.
     */
    public function jsonSerialize()
    {
        return json_encode($this->_parameters);
    }

    /**
     * Count elements of an object.
     *
     * @link http://php.net/manual/en/countable.count.php
     *
     * @return int The custom count as an integer.
     *             The return value is cast to an integer.
     */
    public function count(): int
    {
        return count($this->_parameters);
    }

    /**
     * Returns the array representation of this instance.
     *
     * @return array The array representation of {@see RequestData}
     */
    public function toArray(): array
    {
        return $this->_parameters;
    }
}
