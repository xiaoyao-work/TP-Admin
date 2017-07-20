<?php
namespace Think\Http;

/**
 * Hhailuo HTTP Request
 *
 * This class provides a human-friendly interface to the Hhailuo environment variables;
 * environment variables are passed by reference and will be modified directly.
 *
 * @package Hhailuo
 *
 * @author  逍遥·李志亮
 *
 * @since   1.0.0
 */
class Request {
    const METHOD_HEAD     = 'HEAD';
    const METHOD_GET      = 'GET';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_OVERRIDE = '_METHOD';

    /**
     * @var array
     */
    protected static $formDataMediaTypes = ['application/x-www-form-urlencoded'];

    /**
     * Application Environment
     * @var \Think\Environment
     */
    protected $env;

    /**
     * HTTP Headers
     * @var \Think\Http\Headers
     */
    public $headers;

    /**
     * HTTP Cookies
     * @var \Think\Helper\Set
     */
    public $cookies;

    /**
     * Constructor
     * @param \Think\Environment $env
     */
    public function __construct(\Think\Environment $env) {
        $this->env     = $env;
        $this->headers = new \Think\Http\Headers(\Think\Http\Headers::extract($env));
        $this->cookies = new \Think\Helper\Set(\Think\Http\Util::parseCookieHeader($env['HTTP_COOKIE']));
    }

    /**
     * Get HTTP method
     * @return string
     */
    public function getMethod() {
        return $this->env['REQUEST_METHOD'];
    }

    /**
     * Is this a GET request?
     * @return bool
     */
    public function isGet() {
        return $this->getMethod() === self::METHOD_GET;
    }

    /**
     * Is this a POST request?
     * @return bool
     */
    public function isPost() {
        return $this->getMethod() === self::METHOD_POST;
    }

    /**
     * Is this a PUT request?
     * @return bool
     */
    public function isPut() {
        return $this->getMethod() === self::METHOD_PUT;
    }

    /**
     * Is this a PATCH request?
     * @return bool
     */
    public function isPatch() {
        return $this->getMethod() === self::METHOD_PATCH;
    }

    /**
     * Is this a DELETE request?
     * @return bool
     */
    public function isDelete() {
        return $this->getMethod() === self::METHOD_DELETE;
    }

    /**
     * Is this a HEAD request?
     * @return bool
     */
    public function isHead() {
        return $this->getMethod() === self::METHOD_HEAD;
    }

    /**
     * Is this a OPTIONS request?
     * @return bool
     */
    public function isOptions() {
        return $this->getMethod() === self::METHOD_OPTIONS;
    }

    /**
     * Is this an AJAX request?
     * @return bool
     */
    public function isAjax() {
        if ($this->params('isajax')) {
            return true;
        } elseif (isset($this->headers['X_REQUESTED_WITH']) && $this->headers['X_REQUESTED_WITH'] === 'XMLHttpRequest') {
            return true;
        }

        return false;
    }

    /**
     * Is this an XHR request?
     * @return bool
     */
    public function isXhr() {
        return $this->isAjax();
    }

    /**
     * Fetch GET and POST data
     *
     * This method returns a union of GET and POST data as a key-value array, or the value
     * of the array key if requested; if the array key does not exist, NULL is returned,
     * unless there is a default value specified.
     *
     * @param  string             $key
     * @param  mixed              $default
     * @return array|mixed|null
     */
    public function params($key = null, $default = null) {
        $union = array_merge($this->get(), $this->post());
        if ($key) {
            return isset($union[$key]) ? $union[$key] : $default;
        }

        return $union;
    }

    /**
     * Fetch GET data
     *
     * This method returns a key-value array of data sent in the HTTP request query string, or
     * the value of the array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string             $key
     * @param  mixed              $default Default return value when key does not exist
     * @return array|mixed|null
     */
    public function get($key = null, $default = null) {
        if (!isset($this->env['hhailuo.request.query_hash'])) {
            $output = [];
            if (function_exists('mb_parse_str') && !isset($this->env['hhailuo.tests.ignore_multibyte'])) {
                mb_parse_str($this->env['QUERY_STRING'], $output);
            } else {
                parse_str($this->env['QUERY_STRING'], $output);
            }
            $this->env['hhailuo.request.query_hash'] = Util::stripSlashesIfMagicQuotes($output);
        }
        if ($key) {
            if (isset($this->env['hhailuo.request.query_hash'][$key])) {
                return $this->env['hhailuo.request.query_hash'][$key];
            } else {
                return $default;
            }
        } else {
            return $this->env['hhailuo.request.query_hash'];
        }
    }

    /**
     * Fetch POST data
     *
     * This method returns a key-value array of data sent in the HTTP request body, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string             $key
     * @param  mixed              $default Default return value when key does not exist
     * @throws \RuntimeException  If environment input is not available
     * @return array|mixed|null
     */
    public function post($key = null, $default = null) {
        if (!isset($this->env['hhailuo.input'])) {
            throw new \RuntimeException('Missing hhailuo.input in environment variables');
        }
        if (!isset($this->env['hhailuo.request.form_hash'])) {
            $this->env['hhailuo.request.form_hash'] = [];
            if ($this->isFormData() && is_string($this->env['hhailuo.input'])) {
                $output = [];
                if (function_exists('mb_parse_str') && !isset($this->env['hhailuo.tests.ignore_multibyte'])) {
                    mb_parse_str($this->env['hhailuo.input'], $output);
                } else {
                    parse_str($this->env['hhailuo.input'], $output);
                }
                $this->env['hhailuo.request.form_hash'] = Util::stripSlashesIfMagicQuotes($output);
            } else {
                $this->env['hhailuo.request.form_hash'] = Util::stripSlashesIfMagicQuotes($_POST);
            }
        }
        if ($key) {
            if (isset($this->env['hhailuo.request.form_hash'][$key])) {
                return $this->env['hhailuo.request.form_hash'][$key];
            } else {
                return $default;
            }
        } else {
            return $this->env['hhailuo.request.form_hash'];
        }
    }

    /**
     * Fetch PUT data (alias for \Think\Http\Request::post)
     * @param  string             $key
     * @param  mixed              $default Default return value when key does not exist
     * @return array|mixed|null
     */
    public function put($key = null, $default = null) {
        return $this->post($key, $default);
    }

    /**
     * Fetch PATCH data (alias for \Think\Http\Request::post)
     * @param  string             $key
     * @param  mixed              $default Default return value when key does not exist
     * @return array|mixed|null
     */
    public function patch($key = null, $default = null) {
        return $this->post($key, $default);
    }

    /**
     * Fetch DELETE data (alias for \Think\Http\Request::post)
     * @param  string             $key
     * @param  mixed              $default Default return value when key does not exist
     * @return array|mixed|null
     */
    public function delete($key = null, $default = null) {
        return $this->post($key, $default);
    }

    /**
     * Fetch COOKIE data
     *
     * This method returns a key-value array of Cookie data sent in the HTTP request, or
     * the value of a array key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string              $key
     * @return array|string|null
     */
    public function cookies($key = null) {
        if ($key) {
            return $this->cookies->get($key);
        }
        return $this->cookies;
    }

    /**
     * Does the Request body contain parsed form data?
     * @return bool
     */
    public function isFormData() {
        $method = isset($this->env['hhailuo.method_override.original_method']) ? $this->env['hhailuo.method_override.original_method'] : $this->getMethod();

        return ($method === self::METHOD_POST && is_null($this->getContentType())) || in_array($this->getMediaType(), self::$formDataMediaTypes);
    }

    /**
     * Get Headers
     *
     * This method returns a key-value array of headers sent in the HTTP request, or
     * the value of a hash key if requested; if the array key does not exist, NULL is returned.
     *
     * @param  string  $key
     * @param  mixed   $default The default value returned if the requested header is not available
     * @return mixed
     */
    public function headers($key = null, $default = null) {
        if ($key) {
            return $this->headers->get($key, $default);
        }
        return $this->headers;
    }

    /**
     * Get Body
     * @return string
     */
    public function getBody() {
        return $this->env['hhailuo.input'];
    }

    /**
     * Get Content Type
     * @return string|null
     */
    public function getContentType() {
        return $this->headers->get('CONTENT_TYPE');
    }

    /**
     * Get Media Type (type/subtype within Content Type header)
     * @return string|null
     */
    public function getMediaType() {
        $contentType = $this->getContentType();
        if ($contentType) {
            $contentTypeParts = preg_split('/\s*[;,]\s*/', $contentType);

            return strtolower($contentTypeParts[0]);
        }

        return null;
    }

    /**
     * Get Media Type Params
     * @return array
     */
    public function getMediaTypeParams() {
        $contentType       = $this->getContentType();
        $contentTypeParams = [];
        if ($contentType) {
            $contentTypeParts       = preg_split('/\s*[;,]\s*/', $contentType);
            $contentTypePartsLength = count($contentTypeParts);
            for ($i = 1; $i < $contentTypePartsLength; $i++) {
                $paramParts                                    = explode('=', $contentTypeParts[$i]);
                $contentTypeParams[strtolower($paramParts[0])] = $paramParts[1];
            }
        }

        return $contentTypeParams;
    }

    /**
     * Get Content Charset
     * @return string|null
     */
    public function getContentCharset() {
        $mediaTypeParams = $this->getMediaTypeParams();
        if (isset($mediaTypeParams['charset'])) {
            return $mediaTypeParams['charset'];
        }

        return null;
    }

    /**
     * Get Content-Length
     * @return int
     */
    public function getContentLength() {
        return $this->headers->get('CONTENT_LENGTH', 0);
    }

    /**
     * Get Host
     * @return string
     */
    public function getHost() {
        if (isset($this->env['HTTP_HOST'])) {
            if (strpos($this->env['HTTP_HOST'], ':') !== false) {
                $hostParts = explode(':', $this->env['HTTP_HOST']);

                return $hostParts[0];
            }

            return $this->env['HTTP_HOST'];
        }

        return $this->env['SERVER_NAME'];
    }

    /**
     * Get Host with Port
     * @return string
     */
    public function getHostWithPort() {
        return sprintf('%s:%s', $this->getHost(), $this->getPort());
    }

    /**
     * Get Port
     * @return int
     */
    public function getPort() {
        return (int) $this->env['SERVER_PORT'];
    }

    /**
     * Get Scheme (https or http)
     * @return string
     */
    public function getScheme() {
        return $this->env['hhailuo.url_scheme'];
    }

    /**
     * Get Script Name (physical path)
     * @return string
     */
    public function getScriptName() {
        return $this->env['SCRIPT_NAME'];
    }

    /**
     * LEGACY: Get Root URI (alias for Hhailuo_Http_Request::getScriptName)
     * @return string
     */
    public function getRootUri() {
        return $this->getScriptName();
    }

    /**
     * Get Path (physical path + virtual path)
     * @return string
     */
    public function getPath() {
        return $this->getScriptName() . $this->getPathInfo();
    }

    /**
     * Get Path Info (virtual path)
     * @return string
     */
    public function getPathInfo() {
        return $this->env['PATH_INFO'];
    }

    /**
     * LEGACY: Get Resource URI (alias for Hhailuo_Http_Request::getPathInfo)
     * @return string
     */
    public function getResourceUri() {
        return $this->getPathInfo();
    }

    /**
     * Get URL (scheme + host [ + port if non-standard ])
     * @return string
     */
    public function getUrl() {
        $url = $this->getScheme() . '://' . $this->getHost();
        if (($this->getScheme() === 'https' && $this->getPort() !== 443) || ($this->getScheme() === 'http' && $this->getPort() !== 80)) {
            $url .= sprintf(':%s', $this->getPort());
        }

        return $url;
    }

    /**
     * 获取完整URL地址
     * @return string
     */
    public function getFullUrl() {
        $this->getUrl() . $this->getResourceUri() . (empty($this->env['QUERY_STRING']) ? '' : '?' . $this->env['QUERY_STRING']);
    }

    /**
     * Get IP
     * @return string
     */
    public function getIp() {
        $keys = ['X_FORWARDED_FOR', 'HTTP_X_FORWARDED_FOR', 'CLIENT_IP', 'REMOTE_ADDR'];
        foreach ($keys as $key) {
            if (isset($this->env[$key])) {
                return $this->env[$key];
            }
        }

        return $this->env['REMOTE_ADDR'];
    }

    /**
     * Get Referrer
     * @return string|null
     */
    public function getReferrer() {
        return $this->headers->get('HTTP_REFERER');
    }

    /**
     * Get Referer (for those who can't spell)
     * @return string|null
     */
    public function getReferer() {
        return $this->getReferrer();
    }

    /**
     * Get User Agent
     * @return string|null
     */
    public function getUserAgent() {
        return $this->headers->get('HTTP_USER_AGENT');
    }
}
