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
    public const RequestedRangeNotSatisfiableCode = 416;
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
        self::ContinueCode                      => 'Continue',
        self::SwitchingProtocolCode             => 'Switching Protocols',

        self::OkCode                            => 'OK',
        self::CreatedCode                       => 'Created',
        self::AcceptedCode                      => 'Accepted',
        self::NonAuthoritativeInformationCode   => 'Non-Authoritative Information',
        self::NoContentCode                     => 'No Content',
        self::ResetContentCode                  => 'Reset Content',
        self::PartialContentCode                => 'Partial Content',

        self::MultipleChoicesCode               => 'Multiple Choices',
        self::MovedPermanentlyCode              => 'Moved Permanently',
        self::FoundCode                         => 'Found',
        self::SeeOtherCode                      => 'See Other',
        self::NotModifiedCode                   => 'Not Modified',
        self::UseProxyCode                      => 'Use Proxy',
        self::TemporaryRedirectCode             => 'Temporary Redirect',
        self::PermanentRedirectCode             => 'Permanent Redirect',

        self::BadRequestCode                    => 'Bad Request',
        self::UnauthorizedCode                  => 'Unauthorized',
        self::PaymentRequiredCode               => 'Payment Required',
        self::ForbiddenCode                     => 'Forbidden',
        self::NotFoundCode                      => 'Not Found',
        self::MethodNotAllowedCode              => 'Method Not Allowed',
        self::NotAcceptableCode                 => 'Not Acceptable',
        self::ProxyAuthenticationRequiredCode   => 'Proxy Authentication Required',
        self::RequestTimeoutCode                => 'Request Timeout',
        self::ConflictCode                      => 'Conflict',
        self::GoneCode                          => 'Gone',
        self::LengthRequiredCode                => 'Length Required',
        self::PreconditionFailedCode            => 'Precondition Failed',
        self::RequestEntityTooLargeCode         => 'Request Entity Too Large',
        self::RequestUriTooLongCode             => 'Request-URI Too Long',
        self::UnsupportedMediaTypeCode          => 'Unsupported Media Type',
        self::RequestedRangeNotSatisfiableCode  => 'Requested Range Not Satisfiable',
        self::ExpectationFailedCode             => 'Expectation Failed',
        self::UnprocessableEntityCode           => 'Unprocessable Entity',
        self::LockedCode                        => 'Locked',
        self::FailedDependencyCode              => 'Failed Dependency',
        self::UpgradeRequiredCode               => 'Upgrade Required',
        self::PreconditionRequiredCode          => 'Precondition Required',
        self::TooManyRequestsCode               => 'Too Many Requests',
        self::RequestHeaderFieldsTooLargeCode   => 'Request Header Fields Too Large',
        self::UnavailableForLegalReasonsCode    => 'Unavailable For Legal Reasons',

        self::InternalServerErrorCode           => 'Internal Server Error',
        self::NotImplementedCode                => 'Not Implemented',
        self::BadGatewayCode                    => 'Bad Gateway',
        self::ServiceUnavailableCode            => 'Service Unavailable',
        self::GatewayTimeoutCode                => 'Gateway Timeout',
        self::HttpVersionNotSupportedCode       => 'HTTP Version Not Supported',
        self::NetworkAuthenticationRequiredCode => 'Network Authentication Required'
    );

    /**
     * @var int
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
     * Returns the string representation of this instance.
     *
     * @return string
     */
    public function __toString()
    {
        return "{$this->_code} {$this->_description}";
    }

    /**
     * Returns the response status code.
     *
     * @return int
     */
    public function getCode(): int
    {
        return $this->_code;
    }

    /**
     * Returns the response status description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * Returns the list of registered status codes and descriptions
     *
     * @return array
     */
    public static function getRegistry(): array
    {
        return self::$_registry;
    }

    /**
     * Add a new response status code in the registry
     *
     * @param int $code The response status code
     * @param string $description The response status description
     */
    public static function registerStatusCode(int $code, string $description)
    {
        self::$_registry[$code] = $description;
    }
}
