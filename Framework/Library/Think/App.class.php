<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
namespace Think;

/**
 * ThinkPHP 应用程序类 执行应用过程管理
 */
class App {

    /**
     * @var \Think\Helper\Set
     */
    public $container;

    /**
     * Constructor
     * @param array $userSettings Associative array of application settings
     */
    public function __construct(array $userSettings = []) {
        // Setup IoC container
        $this->container = new \Think\Helper\Set();
        // $this->container['settings'] = C();

        // Default environment
        $this->container->singleton('environment', function ($c) {
            return \Think\Environment::getInstance();
        });

        // Default request
        $this->container->singleton('request', function ($c) {
            return new \Think\Http\Request($c['environment']);
        });

        // Default router
        $this->container->singleton('router', function ($c) {
            return new \Think\Http\Router();
        });
        $this->caseSensitive = C('URL_CASE_INSENSITIVE');
    }

    /********************************************************************************
     * Routing
     *******************************************************************************/

    /**
     * Add GET|POST|PUT|PATCH|DELETE route
     *
     * Adds a new route to the router with associated callable. This
     * route will only be invoked when the HTTP request's method matches
     * this route's method.
     *
     * ARGUMENTS:
     *
     * First:       string  The URL pattern (REQUIRED)
     * In-Between:  mixed   Anything that returns TRUE for `is_callable` (OPTIONAL)
     * Last:        mixed   Anything that returns TRUE for `is_callable` (REQUIRED)
     *
     * The first argument is required and must always be the
     * route pattern (ie. '/books/:id').
     *
     * The last argument is required and must always be the callable object
     * to be invoked when the route matches an HTTP request.
     *
     * You may also provide an unlimited number of in-between arguments;
     * each interior argument must be callable and will be invoked in the
     * order specified before the route's callable is invoked.
     *
     * USAGE:
     *
     * Think::get('/foo'[, middleware, middleware, ...], callable);
     *
     * @param  array                (See notes above)
     * @return \Think\Http\Route
     */
    protected function mapRoute($args) {
        $pattern  = array_shift($args);
        $callable = array_pop($args);
        $route    = new \Think\Http\Route($pattern, $callable, $this->caseSensitive);
        $this->router->map($route);
        if (count($args) > 0) {
            $route->setMiddleware($args);
        }

        return $route;
    }

    /**
     * Add generic route without associated HTTP method
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function map() {
        $args = func_get_args();

        return $this->mapRoute($args);
    }

    /**
     * Add GET route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function get() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_GET, \Think\Http\Request::METHOD_HEAD);
    }

    /**
     * Add POST route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function post() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_POST);
    }

    /**
     * Add PUT route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function put() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_PUT);
    }

    /**
     * Add PATCH route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function patch() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_PATCH);
    }

    /**
     * Add DELETE route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function delete() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_DELETE);
    }

    /**
     * Add OPTIONS route
     * @see    mapRoute()
     *
     * @return \Think\Http\Route
     */
    public function options() {
        $args = func_get_args();

        return $this->mapRoute($args)->via(\Think\Http\Request::METHOD_OPTIONS);
    }

    /**
     * Route Groups
     *
     * This method accepts a route pattern and a callback all Route
     * declarations in the callback will be prepended by the group(s)
     * that it is in
     *
     * Accepts the same parameters as a standard route so:
     * (pattern, middleware1, middleware2, ..., $callback)
     */
    public function group() {
        $args     = func_get_args();
        $pattern  = array_shift($args);
        $callable = array_pop($args);
        $this->router->pushGroup($pattern, $args);
        if (is_callable($callable)) {
            call_user_func($callable);
        }
        $this->router->popGroup();
    }

    /*
     * Add route for any HTTP method
     * @see    mapRoute()
     * @return \Think\Http\Route
     */
    public function any() {
        $args = func_get_args();

        return $this->mapRoute($args)->via("ANY");
    }

    /********************************************************************************
     * 魔术方法
     *******************************************************************************/

    public function __get($name) {
        return $this->container->get($name);
    }

    public function __set($name, $value) {
        $this->container->set($name, $value);
    }

    public function __isset($name) {
        return $this->container->has($name);
    }

    public function __unset($name) {
        $this->container->remove($name);
    }

    /**
     * 应用程序初始化
     * @access public
     * @return void
     */
    public function init() {
        // 加载动态应用公共文件和配置
        load_ext_file(COMMON_PATH);

        // 日志目录转换为绝对路径 默认情况下存储到公共模块下面
        C('LOG_PATH', realpath(LOG_PATH) . '/Common/');

        // 定义当前请求的系统常量
        define('NOW_TIME', $_SERVER['REQUEST_TIME']);
        define('REQUEST_METHOD', $_SERVER['REQUEST_METHOD']);
        define('IS_GET', $this->request->isGet());
        define('IS_POST', $this->request->isPost());
        define('IS_PUT', $this->request->isPut());
        define('IS_DELETE', $this->request->isDelete());

        if (C('REQUEST_VARS_FILTER')) {
            // 全局安全过滤
            array_walk_recursive($_GET, 'think_filter');
            array_walk_recursive($_POST, 'think_filter');
            array_walk_recursive($_REQUEST, 'think_filter');
        }
        define('IS_AJAX', $this->request->isAjax());

        // TMPL_EXCEPTION_FILE 改为绝对地址
        C('TMPL_EXCEPTION_FILE', realpath(C('TMPL_EXCEPTION_FILE')));

        define('MODULE_PATHINFO_DEPR', C('URL_PATHINFO_DEPR'));

        return;
    }

    public function dispatch() {
        // 路由匹配
        $dispatched    = false;
        $match_routes  = $this->router->getMatchedRoutes($this->request->getMethod(), $this->request->getResourceUri(), $this->request->getHost());
        $current_route = $this->router->getCurrentRoute();
        if ($current_route) {
            try {
                $current_route->execMiddleware();
                $dispatched = false;
                $dispatched = call_user_func_array($current_route->getCallable(), array_values($current_route->getParams()));

                // 未匹配处理
                if ($dispatched === false) {
                    $module = self::getModule($current_route->getModule());
                    $current_domain = $current_route->getDomain();
                    if ($current_domain) {
                        define('BIND_MODULE', $module);
                    }
                    define('MODULE_NAME', $module);
                    $this->loadModule();

                    $controller = self::getController($current_route->getController(), $this->caseSensitive);
                    $action     = self::getAction($current_route->getAction(), $this->caseSensitive);
                    $this->loadController($controller, $action);

                    $controllerSuffix = C('DEFAULT_C_LAYER');
                    $controllerClass  = $module . '\\' . $controllerSuffix . '\\' . $controller . $controllerSuffix;
                    // 应用开始标签
                    Hook::listen('app_begin');
                    // Session初始化
                    if (!IS_CLI) {
                        session(C('SESSION_OPTIONS'));
                    }
                    // 记录应用初始化时间
                    G('initTime');
                    $obj        = new $controllerClass;
                    $dispatched = $this->invokeAction($obj, $action, $current_route->getParams());
                }
            } catch (\ReflectionException $e) {
                // 方法调用发生异常后 引导到__call方法处理
                $method = new \ReflectionMethod($obj, '__call');
                $method->invokeArgs($obj, [$action, '']);
            }
        } else {
            E('路由未匹配~');
        }
    }

    public function loadModule() {
        // 检测模块是否存在
        if (MODULE_NAME && !in_array_case(MODULE_NAME, C('MODULE_DENY_LIST')) && is_dir(APP_PATH . MODULE_NAME)) {
            // 定义当前模块路径
            define('MODULE_PATH', APP_PATH . MODULE_NAME . '/');
            // 定义当前模块的模版缓存路径
            C('CACHE_PATH', CACHE_PATH . MODULE_NAME . '/');
            // 定义当前模块的日志目录
            C('LOG_PATH', realpath(LOG_PATH) . '/' . MODULE_NAME . '/');

            // 模块检测
            Hook::listen('module_check');

            // 加载模块配置文件
            if (is_file(MODULE_PATH . 'Conf/config' . CONF_EXT)) {
                C(load_config(MODULE_PATH . 'Conf/config' . CONF_EXT));
            }

            // 加载应用模式对应的配置文件
            if ('common' != APP_MODE && is_file(MODULE_PATH . 'Conf/config_' . APP_MODE . CONF_EXT)) {
                C(load_config(MODULE_PATH . 'Conf/config_' . APP_MODE . CONF_EXT));
            }

            // 当前应用状态对应的配置文件
            if (APP_STATUS && is_file(MODULE_PATH . 'Conf/' . APP_STATUS . CONF_EXT)) {
                C(load_config(MODULE_PATH . 'Conf/' . APP_STATUS . CONF_EXT));
            }

            // 加载模块别名定义
            if (is_file(MODULE_PATH . 'Conf/alias.php')) {
                Think::addMap(include MODULE_PATH . 'Conf/alias.php');
            }

            // 加载模块tags文件定义
            if (is_file(MODULE_PATH . 'Conf/tags.php')) {
                Hook::import(include MODULE_PATH . 'Conf/tags.php');
            }

            // 加载模块函数文件
            if (is_file(MODULE_PATH . 'Common/function.php')) {
                include MODULE_PATH . 'Common/function.php';
            }
            // 加载模块的扩展配置文件
            load_ext_file(MODULE_PATH);
        } else {
            E(L('_MODULE_NOT_EXIST_') . ':' . MODULE_NAME);
        }

        // URL常量
        define('__SELF__', strip_tags($_SERVER[C('URL_REQUEST_URI')]));
        defined('__APP__') || define('__APP__', $this->environment['SCRIPT_NAME']);
        // 模块URL地址
        $moduleName = defined('MODULE_ALIAS') ? MODULE_ALIAS : MODULE_NAME;
        define('__MODULE__', (defined('BIND_MODULE') || !C('MULTI_MODULE')) ? __APP__ : __APP__ . '/' . ($this->caseSensitive ? strtolower($moduleName) : $moduleName));
    }

    public function loadController($controller, $action) {
        define('CONTROLLER_PATH', self::getSpace(C('VAR_ADDON'), $this->caseSensitive));

        // 兼容TP参数机制
        $var = [];
        try {
            $paths = $this->router->getCurrentRoute()->getParam('params');
        } catch (\InvalidArgumentException $e) {
            $paths = [];
        }
        if (C('URL_PARAMS_BIND') && 1 == C('URL_PARAMS_BIND_TYPE')) {
            // URL参数按顺序绑定变量
            $var = $paths;
        } else {
            preg_replace_callback('/(\w+)\/([^\/]+)/', function ($match) use (&$var) {
                $var[$match[1]] = strip_tags($match[2]);
            }, implode('/', $paths));
        }
        $_GET = array_merge($var, $_GET);

        // 获取控制器和操作名
        define('CONTROLLER_NAME', self::getController($controller, $this->caseSensitive));
        define('ACTION_NAME', self::getAction($action, $this->caseSensitive));

        // 当前控制器的UR地址
        $controllerName = defined('CONTROLLER_ALIAS') ? CONTROLLER_ALIAS : CONTROLLER_NAME;
        define('__CONTROLLER__', __MODULE__ . '/' . ($this->caseSensitive ? parse_name($controllerName) : $controllerName));

        // 当前操作的URL地址
        define('__ACTION__', __CONTROLLER__ . '/' . (defined('ACTION_ALIAS') ? ACTION_ALIAS : ACTION_NAME));
    }

    public function invokeAction($controller, $action, $route_params = []) {
        if (!preg_match('/^[A-Za-z](\w)*$/', $action)) {
            // 非法操作
            throw new \ReflectionException();
        }
        //执行当前操作
        $method = new \ReflectionMethod($controller, $action);
        if ($method->isPublic() && !$method->isStatic()) {
            // URL参数绑定检测
            if ($method->getNumberOfParameters() > 0) {
                $params = $method->getParameters();
                foreach ($params as $param) {
                    $name   = $param->getName();
                    $args[] = isset($route_params[$name]) ? $route_params[$name] : ($param->isDefaultValueAvailable() ? $param->getDefaultValue() : '');
                }
                return $method->invokeArgs($controller, $args);
            } else {
                return $method->invoke($controller);
            }
        } else {
            throw new \ReflectionException();
        }
    }

    /**
     * 获得控制器的命名空间路径 便于插件机制访问
     */
    static private function getSpace($var, $urlCase) {
        $space = !empty($_GET[$var]) ? strip_tags($_GET[$var]) : '';
        unset($_GET[$var]);
        return $space;
    }

    /**
     * 获得实际的控制器名称
     */
    static private function getController($controller_name, $urlCase) {
        $controller = (!empty($controller_name) ? $controller_name : C('DEFAULT_CONTROLLER'));
        if ($maps = C('URL_CONTROLLER_MAP')) {
            if (isset($maps[strtolower($controller)])) {
                // 记录当前别名
                define('CONTROLLER_ALIAS', strtolower($controller));
                // 获取实际的控制器名
                return ucfirst($maps[CONTROLLER_ALIAS]);
            } elseif (array_search(strtolower($controller), $maps)) {
                // 禁止访问原始控制器
                return '';
            }
        }
        if ($urlCase) {
            // URL地址不区分大小写
            // 智能识别方式 user_type 识别到 UserTypeController 控制器
            $controller = parse_name($controller, 1);
        }
        return strip_tags(ucfirst($controller));
    }

    /**
     * 获得实际的操作名称
     */
    static private function getAction($action_name, $urlCase) {
        $action = !empty($action_name) ? $action_name : C('DEFAULT_ACTION');
        if ($maps = C('URL_ACTION_MAP')) {
            if (isset($maps[strtolower(CONTROLLER_NAME)])) {
                $maps = $maps[strtolower(CONTROLLER_NAME)];
                if (isset($maps[strtolower($action)])) {
                    // 记录当前别名
                    define('ACTION_ALIAS', strtolower($action));
                    // 获取实际的操作名
                    if (is_array($maps[ACTION_ALIAS])) {
                        parse_str($maps[ACTION_ALIAS][1], $vars);
                        $_GET = array_merge($_GET, $vars);
                        return $maps[ACTION_ALIAS][0];
                    } else {
                        return $maps[ACTION_ALIAS];
                    }

                } elseif (array_search(strtolower($action), $maps)) {
                    // 禁止访问原始操作
                    return '';
                }
            }
        }
        return strip_tags($urlCase ? strtolower($action) : $action);
    }

    /**
     * 获得实际的模块名称
     */
    static private function getModule($module = '') {
        $module = (!empty($module) ? $module : C('DEFAULT_MODULE'));
        if ($maps = C('URL_MODULE_MAP')) {
            if (isset($maps[strtolower($module)])) {
                // 记录当前别名
                define('MODULE_ALIAS', strtolower($module));
                // 获取实际的模块名
                return ucfirst($maps[MODULE_ALIAS]);
            } elseif (array_search(strtolower($module), $maps)) {
                // 禁止访问原始模块
                return '';
            }
        }
        return strip_tags(ucfirst($module));
    }

    /**
     * 运行应用实例 入口文件使用的快捷方法
     * @access public
     * @return void
     */
    public function run() {
        // 应用初始化标签
        Hook::listen('app_init');
        $this->init();
        $this->dispatch();
        // 应用结束标签
        Hook::listen('app_end');
        return;
    }

}
