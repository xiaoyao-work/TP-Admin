<?php
namespace Think;

/**
 * Environment
 *
 * This class creates and returns a key/value array of common
 * environment variables for the current HTTP request.
 *
 * This is a singleton class; derived environment variables will
 * be common across multiple hhailuo applications.
 *
 * This class matches the Rack (Ruby) specification as closely
 * as possible. More information available below.
 */
class Environment implements \ArrayAccess, \IteratorAggregate {
    /**
     * @var array
     */
    protected $properties;

    /**
     * @var \Think\Environment
     */
    protected static $environment;

    /**
     * Special-case HTTP headers that are otherwise unidentifiable as HTTP headers.
     * Typically, HTTP headers in the $_SERVER array will be prefixed with
     * `HTTP_` or `X_`. These are not so we list them here for later reference.
     *
     * @var array
     */
    protected static $special = [
        'CONTENT_TYPE',
        'CONTENT_LENGTH',
        'PHP_AUTH_USER',
        'PHP_AUTH_PW',
        'PHP_AUTH_DIGEST',
        'AUTH_TYPE',
    ];

    /**
     * Get environment instance (singleton)
     *
     * This creates and/or returns an environment instance (singleton)
     * derived from $_SERVER variables. You may override the global server
     * variables by using `\Think\Environment::mock()` instead.
     *
     * @param  bool                $refresh Refresh properties using global server variables?
     * @return \Think\Environment
     */
    public static function getInstance($refresh = false) {
        if (is_null(self::$environment) || $refresh) {
            self::$environment = new self();
        }

        return self::$environment;
    }

    /**
     * Get mock environment instance
     *
     * @param  array               $userSettings
     * @return \Think\Environment
     */
    public static function mock($userSettings = []) {
        $defaults = [
            'REQUEST_METHOD'  => 'GET',
            'SCRIPT_NAME'     => '',
            'PATH_INFO'       => '',
            'QUERY_STRING'    => '',
            'SERVER_NAME'     => 'localhost',
            'SERVER_PORT'     => 80,
            'ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'ACCEPT_LANGUAGE' => 'en-US,en;q=0.8',
            'ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.3',
            'USER_AGENT'      => 'hhailuo Framework',
            'REMOTE_ADDR'     => '127.0.0.1',
            'hhailuo.url_scheme' => 'http',
            'hhailuo.input'      => '',
            'hhailuo.errors'     => @fopen('php://stderr', 'w'),
        ];
        self::$environment = new self(array_merge($defaults, $userSettings));

        return self::$environment;
    }

    /**
     * Constructor (private access)
     *
     * @param array|null $settings If present, these are used instead of global server variables
     */
    private function __construct($settings = null) {
        if ($settings) {
            $this->properties = $settings;
        } else {
            $env = [];

            //The HTTP request method
            $env['REQUEST_METHOD'] = $_SERVER['REQUEST_METHOD'];

            //The IP
            $env['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];

            // Server params
            $scriptName  = $_SERVER['SCRIPT_NAME'];  // <-- "/foo/index.php"
            $requestUri  = $_SERVER['REQUEST_URI'];  // <-- "/foo/bar?test=abc" or "/foo/index.php/bar?test=abc"
            $queryString = isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : ''; // <-- "test=abc" or ""

            // Physical path
            if (strpos($requestUri, $scriptName) !== false) {
                $physicalPath = $scriptName; // <-- Without rewriting
            } else {
                $physicalPath = str_replace('\\', '', dirname($scriptName)); // <-- With rewriting
            }
            $env['SCRIPT_NAME'] = rtrim($physicalPath, '/'); // <-- Remove trailing slashes

            // Virtual path
            $env['PATH_INFO'] = $requestUri;
            if (substr($requestUri, 0, strlen($physicalPath)) == $physicalPath) {
                $env['PATH_INFO'] = substr($requestUri, strlen($physicalPath)); // <-- Remove physical path
            }
            $env['PATH_INFO'] = str_replace('?' . $queryString, '', $env['PATH_INFO']); // <-- Remove query string
            $env['PATH_INFO'] = '/' . ltrim($env['PATH_INFO'], '/');                    // <-- Ensure leading slash

            // 去除URL后缀
            if (C('URL_HTML_SUFFIX')) {
                $env['PATH_INFO'] = preg_replace('/\.('.trim(C('URL_HTML_SUFFIX'),'.').')$/i', '', $env['PATH_INFO']);
            }

            // Query string (without leading "?")
            $env['QUERY_STRING'] = $queryString;

            //Name of server host that is running the script
            $env['SERVER_NAME'] = $_SERVER['SERVER_NAME'];

            //Number of server port that is running the script
            $env['SERVER_PORT'] = isset($_SERVER['SERVER_PORT']) ? $_SERVER['SERVER_PORT'] : 80;

            //HTTP request headers (retains HTTP_ prefix to match $_SERVER)
            $headers = static::extract($_SERVER);
            foreach ($headers as $key => $value) {
                $env[$key] = $value;
            }

            //Is the application running under HTTPS or HTTP protocol?
            $env['hhailuo.url_scheme'] = empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === 'off' ? 'http' : 'https';

            //Input stream (readable one time only; not available for multipart/form-data requests)
            $rawInput = @file_get_contents('php://input');
            if (!$rawInput) {
                $rawInput = '';
            }
            $env['hhailuo.input'] = $rawInput;

            //Error stream
            $env['hhailuo.errors'] = @fopen('php://stderr', 'w');

            $this->properties = $env;
        }
    }

    /**
     * Array Access: Offset Exists
     */
    public function offsetExists($offset) {
        return isset($this->properties[$offset]);
    }

    /**
     * Array Access: Offset Get
     */
    public function offsetGet($offset) {
        if (isset($this->properties[$offset])) {
            return $this->properties[$offset];
        }

        return null;
    }

    /**
     * Array Access: Offset Set
     */
    public function offsetSet($offset, $value) {
        $this->properties[$offset] = $value;
    }

    /**
     * Array Access: Offset Unset
     */
    public function offsetUnset($offset) {
        unset($this->properties[$offset]);
    }

    /**
     * IteratorAggregate
     *
     * @return \ArrayIterator
     */
    public function getIterator() {
        return new \ArrayIterator($this->properties);
    }

    /**
     * Extract HTTP headers from an array of data (e.g. $_SERVER)
     * @param  array   $data
     * @return array
     */
    protected static function extract($data) {
        $results = [];
        foreach ($data as $key => $value) {
            $key = strtoupper($key);
            if (strpos($key, 'X_') === 0 || strpos($key, 'HTTP_') === 0 || in_array($key, static::$special)) {
                if ($key === 'HTTP_CONTENT_LENGTH') {
                    continue;
                }
                $results[$key] = $value;
            }
        }

        return $results;
    }
}
