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

namespace ElementaryFramework\WaterPipe\Streams;

use Exception;
use Closure;

use ElementaryFramework\Core\Events\WithEvents;
use ElementaryFramework\Core\Streams\Events\StreamEvent;
use ElementaryFramework\Core\Streams\IReadableStream;
use ElementaryFramework\Core\Streams\IWritableStream;
use ElementaryFramework\WaterPipe\HTTP\Request\Request;

/**
 * A Stream used to handle large raw file uploads.
 */
class RawFileUploadStream implements IReadableStream
{
    use WithEvents;

    private $_closed;

    private $_paused;

    private $_handle;

    private $_receiving;

    private $_destinationHandle;

    /**
     * Creates a new {@link FileUploadStream} from the given request to the given destination.
     * 
     * @param Request $request     The request from which read the uploaded file content.
     * @param string  $destination The destination file path in which write the content read from request.
     * @param bool    $append      Define if the destination file will be overwritten or appended with new content.
     */
    public function __construct(Request $request, string $destination = null, bool $append = false)
    {
        if ($destination !== null) {
            $this->_destinationHandle = fopen($destination, $append ? "a" : "w");
        }

        $this->_handle = fopen('php://input', "r");

        $this->_closed = false;
        $this->_paused = false;

        $this->on(
            StreamEvent::EVENT_END,
            Closure::bind(function () {
                $this->_receiving = false;
            }, $this)
        );

        $this->on(
            StreamEvent::EVENT_DATA,
            Closure::bind(function ($data) {
                if ($this->_destinationHandle != null) {
                    fwrite($this->_destinationHandle, $data);
                }
            }, $this)
        );

        $this->on(
            StreamEvent::EVENT_CLOSE,
            Closure::bind(function () {
                if ($this->_destinationHandle != null) {
                    fclose($this->_destinationHandle);
                }
            }, $this)
        );
    }

    /**
     * @inheritDoc
     */
    public function close(): void
    {
        if ($this->_closed)
            return;

        if ($this->_closed = fclose($this->_handle)) {
            $this->emit(StreamEvent::EVENT_CLOSE);
        } else {
            $this->emit(
                StreamEvent::EVENT_ERROR,
                new Exception("Unable to close the upload stream.")
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function isReadable(): bool
    {
        return !$this->_closed && !feof($this->_handle);
    }

    /**
     * @inheritDoc
     */
    public function pause(): void
    {
        if ($this->_paused)
            return;

        $this->_paused = true;
    }

    /**
     * @inheritDoc
     */
    public function resume(): void
    {
        if (!$this->_paused)
            return;

        $this->_paused = false;
    }

    /**
     * @inheritDoc
     */
    public function pipe(IWritableStream $destination, bool $autoEnd = true): IReadableStream
    {
        if (!$this->isReadable())
            return null;

        if (!$destination->isWritable())
            $this->pause();

        if ($autoEnd) {
            $this->on(
                StreamEvent::EVENT_END,
                function ($data) use (&$destination) {
                    $destination->end($data);
                }
            );
        }

        $destination->emit(
            StreamEvent::EVENT_PIPE,
            $this
        );

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function read(int $length): void
    {
        if ($this->isReadable()) {
            $data = fread($this->_handle, $length);

            if ($data === false) {
                $this->emit(
                    StreamEvent::EVENT_ERROR,
                    new Exception("Unable to read the upload stream.")
                );
            } else {
                $this->emit(
                    StreamEvent::EVENT_DATA,
                    $data
                );
            }
        } else if (feof($this->_handle) && !$this->_closed) {
            $this->emit(StreamEvent::EVENT_END);
        }
    }

    /**
     * Start receiving data from request.
     *
     * @return void
     */
    public function receive()
    {
        $this->_receiving = true;

        while ($this->_receiving) {
            $this->read(1024);
        }

        $this->close();
    }

    /**
     * Closes the stream when the instance is being destructed.
     */
    function __destruct()
    {
        $this->close();
    }
}
