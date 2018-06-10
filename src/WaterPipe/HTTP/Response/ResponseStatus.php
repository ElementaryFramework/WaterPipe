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

namespace ElementaryFramework\WaterPipe\HTTP\Response;

class ResponseStatus
{
    public const ContinueCode = 100;
    public const SwitchingProtocolCode = 101;

    public const OkCode = 200;
    public const CreatedCode = 201;
    public const AcceptedCode = 202;
    public const NonAuthoritativeInformationCode = 203;
    public const NoContentCode = 204;
    public const ResetContentCode = 205;
    public const PartialContentCode = 206;

    public const MultipleChoicesCode = 300;
    public const MovedPermanentlyCode = 301;
    public const FoundCode = 302;
    public const SeeOtherCode = 303;
    public const NotModifiedCode = 304;
    public const UseProxyCode = 305;
    public const TemporaryRedirectCode = 307;
    public const PermanentRedirectCode = 308;

    public const BadRequestCode = 400;
    public const UnauthorizedCode = 401;
    public const PaymentRequiredCode = 402;
    public const ForbiddenCode = 403;
    public const NotFoundCode = 404;
    public const MethodNotAllowedCode = 405;
    public const NotAcceptableCode = 406;
    public const ProxyAuthenticationRequiredCode = 407;
    public const RequestTimeoutCode = 408;
    public const ConflictCode = 409;
    public const GoneCode = 410;
    public const LengthRequiredCode = 411;
    public const PreconditionFailedCode = 412;
    public const RequestEntityTooLargeCode = 413;
    public const RequestUriTooLongCode = 414;
    public const UnsupportedMediaTypeCode = 415;
    public const RequestRangeNotSatisfiableCode = 416;
    public const ExpectationFailedCode = 417;
    public const MisdirectedRequestCode = 421;
    public const UnprocessableEntityCode = 422;
    public const LockedCode = 423;
    public const FailedDependencyCode = 424;
    public const UpgradeRequiredCode = 426;
    public const PreconditionRequiredCode = 428;
    public const TooManyRequestsCode = 429;
    public const RequestHeaderFieldsTooLargeCode = 431;
    public const UnavailableForLegalReasonsCode = 451;

    public const InternalServerErrorCode = 500;
    public const NotImplementedCode = 501;
    public const BadGatewayCode = 502;
    public const ServiceUnavailableCode = 503;
    public const GatewayTimeoutCode = 504;
    public const HttpVersionNotSupportedCode = 505;
    public const NetworkAuthenticationRequiredCode = 511;

    /**
     * @var string[] The array of registered status codes and their descriptions.
     */
    private static $_registry = array(
        100 => 'Continue',
        101 => 'Switching Protocols',

        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        308 => 'Permanent Redirect',

        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        422 => 'Unprocessable Entity',
        423 => 'Locked',
        424 => 'Failed Dependency',
        426 => 'Upgrade Required',
        428 => 'Precondition Required',
        429 => 'Too Many Requests',
        431 => 'Request Header Fields Too Large',
        451 => 'Unavailable For Legal Reasons',

        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        511 => 'Network Authentication Required'
    );

    /**
     * @var integer
     */
    private $_code;

    /**
     * @var string
     */
    private $_description;

    /**
     * ResponseStatus constructor.
     * @param int $code
     * @throws \Exception
     */
    public function __construct($code = 200)
    {
        if (isset(self::$_registry[$code])) {
            $this->_code = $code;
            $this->_description = self::$_registry[$code];
        } else {
            throw new \Exception("Unregistered response status code: \"{$code}\"");
        }
    }

    /**
     * @return int
     */
    public function getCode(): int
    {
        return $this->_code;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * @return array
     */
    public static function getRegistry(): array
    {
        return self::$_registry;
    }

    public static function registerStatusCode(int $code, string $description)
    {
        self::$_registry[$code] = $description;
    }
}