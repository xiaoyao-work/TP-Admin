<?php
namespace Think\Http;

/**
 * Route
 * @package Hhailuo
 *
 * @author  逍遥·李志亮
 *
 * @since   1.0.0
 */
class Route {
    /**
     * @var string The route pattern (e.g. "/books/:id")
     */
    protected $pattern;

    /**
     * @var string The route domain
     */
    protected $domain = '';

    /**
     * @var string 路由归属组 兼容TP分组机制
     */
    protected $module = '';

    /**
     * @var string controller
     */
    protected $controller = '';

    /**
     * @var string action
     */
    protected $action = '';

    /**
     * @var string 路由对应的类
     */
    protected $controllerClass = '';

    /**
     * @var mixed The route callable
     */
    protected $callable;

    /**
     * @var mixed The route origin callable
     */
    protected $stringCallable;

    /**
     * @var mixed The route origin callable
     */
    protected $arrayCallable;

    /**
     * @var array Conditions for this route's URL parameters
     */
    protected $conditions = [];

    /**
     * @var array Default conditions applied to all route instances
     */
    protected static $defaultConditions = [];

    /**
     * @var string The name of this route (optional)
     */
    protected $name;

    /**
     * @var array Key-value array of URL parameters
     */
    protected $params = [];

    /**
     * @var array value array of URL parameter names
     */
    protected $paramNames = [];

    /**
     * @var array key array of URL parameter names with + at the end
     */
    protected $paramNamesPath = [];

    /**
     * @var array HTTP methods supported by this Route
     */
    protected $methods = [];

    /**
     * @var array[Callable] Middleware to be run before only this route instance
     */
    protected $middleware = [];

    /**
     * @var bool Whether or not this route should be matched in a case-sensitive manner
     */
    protected $caseSensitive;

    /**
     * Constructor
     * @param string $pattern       The URL pattern (e.g. "/books/:id")
     * @param mixed  $callable      Anything that returns TRUE for is_callable()
     * @param bool   $caseSensitive Whether or not this route should be matched in a case-sensitive manner
     */
    public function __construct($pattern, $callable, $caseSensitive = true) {
        $this->setPattern($pattern);
        $this->setCallable($callable);
        $this->setConditions(self::getDefaultConditions());
        $this->caseSensitive     = $caseSensitive;
    }

    /**
     * Set default route conditions for all instances
     * @param array $defaultConditions
     */
    public static function setDefaultConditions(array $defaultConditions) {
        self::$defaultConditions = $defaultConditions;
    }

    /**
     * Get default route conditions for all instances
     * @return array
     */
    public static function getDefaultConditions() {
        return self::$defaultConditions;
    }

    /**
     * Get route pattern
     * @return string
     */
    public function getPattern() {
        return $this->pattern;
    }

    /**
     * Set route pattern
     * @param string $pattern
     */
    public function setPattern($pattern) {
        $this->pattern = $pattern;
    }

    /**
     * 获取路由域名
     * @return string
     */
    public function getDomain() {
        return $this->domain;
    }

    /**
     * 设置路由域名
     */
    public function setDomain($domain) {
        $this->domain = $domain;
    }

    /**
     * 获取路由归属模型 兼容TP分组机制
     * @return string
     */
    public function getModule() {
        return $this->module;
    }

    /**
     * 设置路由归属模型 兼容TP
     */
    public function setModule($module) {
        $this->module = $module;
    }

    /**
     * 获取controller 兼容TP
     * @return string
     */
    public function getController() {
        return $this->controller;
    }

    /**
     * 设置controller 兼容TP
     */
    public function setController($controller) {
        $this->controller = $controller;
    }

    /**
     * 获取Action 兼容TP
     * @return string
     */
    public function getAction() {
        return $this->action;
    }

    /**
     * 设置Action 兼容TP
     */
    public function setAction($action) {
        $this->action = $action;
    }

    /**
     * Get route callable
     * @return mixed
     */
    public function getCallable() {
        return $this->callable;
    }

    /**
     * Set route callable
     * @param  mixed                     $callable
     * @throws \InvalidArgumentException If argument is not callable
     */
    public function setCallable($callable) {
        $matches = [];
        if (is_string($callable) && preg_match('!^([^\:]+)\:([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)$!', $callable, $matches)) {
            $class  = $matches[1];
            $method = $matches[2];
            $this->stringCallable = $callable;
            $callable = function () use ($class, $method) {
                $this->controller = $class;
                $this->action     = $method;
                return false;
            };
        } elseif (is_array($callable)) {
            $this->arrayCallable = $callable;
            $callable = function () use ($callable) {
                if (empty($this->module) && isset($callable['module'])) {
                    $this->module = isset($this->getParam[$callable['module']]) ? $this->getParam[$callable['module']] : '';
                }
                $this->controller = isset($this->params[$callable['controller']]) ? $this->params[$callable['controller']] : '';
                $this->action     = isset($this->params[$callable['action']]) ? $this->params[$callable['action']] : '';
                return false;
            };
        }
        if (!is_callable($callable)) {
            throw new \InvalidArgumentException('Route callable must be callable');
        }
        $this->callable = $callable;
    }

    /**
     * Get route conditions
     * @return array
     */
    public function getConditions() {
        return $this->conditions;
    }

    /**
     * Set route conditions
     * @param array $conditions
     */
    public function setConditions(array $conditions) {
        $this->conditions = $conditions;
    }

    /**
     * Get route name
     * @return string|null
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set route name
     * @param string $name
     */
    public function setName($name) {
        $this->name = (string) $name;
    }

    /**
     * Get route parameters
     * @return array
     */
    public function getParams() {
        return $this->params;
    }

    /**
     * Set route parameters
     * @param array $params
     */
    public function setParams($params) {
        $this->params = $params;
    }

    /**
     * Get route parameter value
     * @param  string                    $index Name of URL parameter
     * @throws \InvalidArgumentException If route parameter does not exist at index
     * @return string
     */
    public function getParam($index) {
        if (!isset($this->params[$index])) {
            throw new \InvalidArgumentException('Route parameter does not exist at specified index');
        }

        return $this->params[$index];
    }

    /**
     * Set route parameter value
     * @param  string                    $index Name of URL parameter
     * @param  mixed                     $value The new parameter value
     * @throws \InvalidArgumentException If route parameter does not exist at index
     */
    public function setParam($index, $value) {
        if (!isset($this->params[$index])) {
            throw new \InvalidArgumentException('Route parameter does not exist at specified index');
        }
        $this->params[$index] = $value;
    }

    /**
     * Add supported HTTP method(s)
     */
    public function setHttpMethods() {
        $args          = func_get_args();
        $this->methods = $args;
    }

    /**
     * Get supported HTTP methods
     * @return array
     */
    public function getHttpMethods() {
        return $this->methods;
    }

    /**
     * Append supported HTTP methods
     */
    public function appendHttpMethods() {
        $args = func_get_args();
        if (count($args) && is_array($args[0])) {
            $args = $args[0];
        }
        $this->methods = array_merge($this->methods, $args);
    }

    /**
     * Append supported HTTP methods (alias for Route::appendHttpMethods)
     * @return \Think\Http\Route
     */
    public function via() {
        $args = func_get_args();
        if (count($args) && is_array($args[0])) {
            $args = $args[0];
        }
        $this->methods = array_merge($this->methods, $args);

        return $this;
    }

    /**
     * Detect support for an HTTP method
     * @param  string $method
     * @return bool
     */
    public function supportsHttpMethod($method) {
        return in_array($method, $this->methods);
    }

    /**
     * 检测域名是否匹配
     * @return bool
     */
    public function supportsDomain($domain) {
        return empty($this->domain) || $this->domain == $domain || $this->matcheDomain($domain);
    }

    /**
     * 域名正则匹配
     * @param  string $domain 域名
     * @return bool
     */
    public function matcheDomain($domain) {
        $result = false;
        $quote_key = str_replace('.', '\\.', $this->domain);
        // 匹配域名
        if (preg_match('/\{(.*)\}/', $quote_key, $match_origin)) {
            $sub_domain_key = $match_origin[1];
            // 转换成标准整正则表达式
            $key = str_replace('{' . $sub_domain_key . '}', '([^\.]*)', $quote_key);
            $result = preg_match('/' . $key . '/', $domain, $match);
        }
        if ($result) {
            $this->domain = $domain;
        }
        return $result;
    }

    /**
     * Get middleware
     * @return array[Callable]
     */
    public function getMiddleware() {
        return $this->middleware;
    }

    /**
     * Set middleware
     *
     * This method allows middleware to be assigned to a specific Route.
     * If the method argument `is_callable` (including callable arrays!),
     * we directly append the argument to `$this->middleware`. Else, we
     * assume the argument is an array of callables and merge the array
     * with `$this->middleware`.  Each middleware is checked for is_callable()
     * and an InvalidArgumentException is thrown immediately if it isn't.
     *
     * @param  Callable|array[Callable]
     * @throws \InvalidArgumentException  If argument is not callable or not an array of callables.
     * @return \Think\Http\Route
     */
    public function setMiddleware($middleware) {
        if (is_callable($middleware)) {
            $this->middleware[] = $middleware;
        } elseif (is_array($middleware)) {
            foreach ($middleware as $callable) {
                if (!is_callable($callable)) {
                    throw new \InvalidArgumentException('All Route middleware must be callable');
                }
            }
            $this->middleware = array_merge($this->middleware, $middleware);
        } else {
            throw new \InvalidArgumentException('Route middleware must be callable or an array of callables');
        }

        return $this;
    }

    /**
     * Matches URI?
     *
     * Parse this route's pattern, and then compare it to an HTTP resource URI
     * This method was modeled after the techniques demonstrated by Dan Sosedoff at:
     *
     * http://blog.sosedoff.com/2009/09/20/rails-like-php-url-router/
     *
     * @param  string $resourceUri A Request URI
     * @return bool
     */
    public function matches($resourceUri) {
        //Convert URL params into regex patterns, construct a regex for this route, init params
        $patternAsRegex = preg_replace_callback(
            '#:([\w]+)\+?#',
            [$this, 'matchesCallback'],
            str_replace(')', ')?', (string) $this->pattern)
        );
        if (substr($this->pattern, -1) === '/') {
            $patternAsRegex .= '?';
        }

        $regex = '#^' . $patternAsRegex . '$#';

        if ($this->caseSensitive === false) {
            $regex .= 'i';
        }
        //Cache URL params' names and values if this route matches the current HTTP request
        if (!preg_match($regex, $resourceUri, $paramValues)) {
            return false;
        }
        foreach ($this->paramNames as $name) {
            if (isset($paramValues[$name])) {
                if (isset($this->paramNamesPath[$name])) {
                    $this->params[$name] = explode('/', urldecode($paramValues[$name]));
                } else {
                    $this->params[$name] = urldecode($paramValues[$name]);
                }
            }
        }

        return true;
    }

    /**
     * Convert a URL parameter (e.g. ":id", ":id+") into a regular expression
     * @param  array  $m      URL parameters
     * @return string Regular expression for URL parameter
     */
    protected function matchesCallback($m) {
        $this->paramNames[] = $m[1];
        if (isset($this->conditions[$m[1]])) {
            return '(?P<' . $m[1] . '>' . $this->conditions[$m[1]] . ')';
        }
        if (substr($m[0], -1) === '+') {
            $this->paramNamesPath[$m[1]] = 1;

            return '(?P<' . $m[1] . '>.+)';
        }

        return '(?P<' . $m[1] . '>[^/]+)';
    }

    /**
     * Set route name
     * @param  string              $name The name of the route
     * @return \Think\Http\Route
     */
    public function name($name) {
        $this->setName($name);

        return $this;
    }

    /**
     * Merge route conditions
     * @param  array               $conditions Key-value array of URL parameter conditions
     * @return \Think\Http\Route
     */
    public function conditions(array $conditions) {
        $this->conditions = array_merge($this->conditions, $conditions);

        return $this;
    }

    /**
     * 执行路由中间键
     */
    public function execMiddleware() {
        foreach ($this->middleware as $mw) {
            call_user_func_array($mw, [$this]);
        }
    }

}
