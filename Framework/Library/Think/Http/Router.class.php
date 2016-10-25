<?php
namespace Think\Http;

/**
 * Router
 *
 * This class organizes, iterates, and dispatches \Think\Http\Route objects.
 * @package Hhailuo
 *
 * @author  逍遥·李志亮
 *
 * @since   1.0.0
 */
class Router {
    /**
     * @var Route The current route (most recently dispatched)
     */
    protected $currentRoute;

    /**
     * @var array Lookup hash of all route objects
     */
    protected $routes;

    /**
     * @var array Lookup hash of named route objects, keyed by route name (lazy-loaded)
     */
    protected $namedRoutes;

    /**
     * @var array  (lazy-loaded)
     */
    protected $actionRoutes;

    /**
     * @var array Array of route objects that match the request URI (lazy-loaded)
     */
    protected $matchedRoutes;

    /**
     * @var array Array containing all route groups
     */
    protected $routeGroups;

    /**
     * @var bool 是否匹配所有
     */
    protected $routeRcursion = false;

    /**
     * Constructor
     */
    public function __construct() {
        $this->routes      = [];
        $this->routeGroups = [];
    }

    /**
     * Get Current Route object or the first matched one if matching has been performed
     * @return \Think\Http\Route|null
     */
    public function getCurrentRoute() {
        if ($this->currentRoute !== null) {
            return $this->currentRoute;
        }

        if (is_array($this->matchedRoutes) && count($this->matchedRoutes) > 0) {
            return $this->matchedRoutes[0];
        }

        return null;
    }

    /**
     * Return route objects that match the given HTTP method and URI
     * @param  string                     $httpMethod  The HTTP method to match against
     * @param  string                     $resourceUri The resource URI to match against
     * @param  string                     $domain      请求域名
     * @param  bool                       $reload      Should matching routes be re-parsed?
     * @return array[\Think\Http\Route]
     */
    public function getMatchedRoutes($httpMethod, $resourceUri, $domain, $reload = false) {
        if ($reload || is_null($this->matchedRoutes)) {
            $this->matchedRoutes = [];
            foreach ($this->routes as $route) {
                if (!$route->supportsDomain($domain) || (!$route->supportsHttpMethod($httpMethod) && !$route->supportsHttpMethod("ANY"))) {
                    continue;
                }
                if ($route->matches($resourceUri)) {
                    $this->matchedRoutes[] = $route;
                    if (!$this->routeRcursion) {
                        break;
                    }
                }
            }
        }
        return $this->matchedRoutes;
    }

    /**
     * Add a route object to the router
     * @param \Think\Http\Route $route The Slim Route
     */
    public function map(\Think\Http\Route $route) {
        list($groupPattern, $groupMiddleware) = $this->processGroups();

        $route->setPattern($groupPattern . $route->getPattern());
        // 新增域名绑定机制
        $route->setDomain($this->routeDomain);
        // 兼容TP分组机制
        $route->setModule($this->module);

        $this->routes[] = $route;

        foreach ($groupMiddleware as $middleware) {
            $route->setMiddleware($middleware);
        }
    }

    /**
     * A helper function for processing the group's pattern and middleware
     * @return array Returns an array with the elements: pattern, middlewareArr
     */
    protected function processGroups() {
        $pattern    = "";
        $middleware = [];
        foreach ($this->routeGroups as $group) {
            $pattern .= $group['pattern'];
            if (is_array($group['middleware'])) {
                $middleware = array_merge($middleware, $group['middleware']);
            }
        }
        return [$pattern, $middleware];
    }

    /**
     * Add a route group to the array
     * @param  array|string $group      The group pattern (ie. "/books/:id" , ['prefix' => "/books/:id", 'domain' => 'www.hhailuo.com'])
     * @param  array|null   $middleware Optional parameter array of middleware
     * @return int          The index of the new group
     */
    public function pushGroup($group, $middleware = []) {
        if (is_array($group)) {
            $group             = array_merge(['prefix' => '', 'domain' => '', 'module' => C('DEFAULT_MODULE')], $group);
            $pattern           = $group['prefix'];
            $this->routeDomain = $group['domain'];
            $this->module      = $group['module'];
        } else {
            $pattern = $group;
        }
        return array_push($this->routeGroups, ['pattern' => $pattern, 'middleware' => $middleware]);
    }

    /**
     * Removes the last route group from the array
     * @return bool True if successful, else False
     */
    public function popGroup() {
        $this->routeDomain = null;
        return (array_pop($this->routeGroups) !== null);
    }

    /**
     * Get URL for named route
     * @param  string            $name   The name of the route
     * @param  array             $params Associative array of URL parameter names and replacement values
     * @throws \RuntimeException If named route not found
     * @return string            The URL for the given route populated with provided replacement values
     */
    public function urlFor($name, $params = []) {
        if (!$this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route not found for name: ' . $name);
        }
        $search = [];
        foreach ($params as $key => $value) {
            $search[] = '#:' . preg_quote($key, '#') . '\+?(?!\w)#';
        }
        $route = $this->getNamedRoute($name);
        $pattern = preg_replace($search, $params, $route->getPattern());

        //Remove remnants of unpopulated, trailing optional pattern segments, escaped special characters
        return $route->getDomain() . preg_replace('#\(/?:.+\)|\(|\)|\\\\#', '', $pattern);
    }

    /**
     * Add named route
     * @param  string            $name  The route name
     * @param  \Think\Http\Route $route The route object
     * @throws \RuntimeException If a named route already exists with the same name
     */
    public function addNamedRoute($name, \Think\Http\Route $route) {
        if ($this->hasNamedRoute($name)) {
            throw new \RuntimeException('Named route already exists with name: ' . $name);
        }
        $this->namedRoutes[(string) $name] = $route;
    }

    /**
     * Has named route
     * @param  string $name The route name
     * @return bool
     */
    public function hasNamedRoute($name) {
        $this->getNamedRoutes();

        return isset($this->namedRoutes[(string) $name]);
    }

    /**
     * Get named route
     * @param  string                   $name
     * @return \Think\Http\Route|null
     */
    public function getNamedRoute($name) {
        $this->getNamedRoutes();
        if ($this->hasNamedRoute($name)) {
            return $this->namedRoutes[(string) $name];
        }

        return null;
    }

    /**
     * Get named routes
     * @return \ArrayIterator
     */
    public function getNamedRoutes() {
        if (is_null($this->namedRoutes)) {
            $this->namedRoutes = [];
            foreach ($this->routes as $route) {
                if ($route->getName() !== null) {
                    $this->addNamedRoute($route->getName(), $route);
                }
            }
        }

        return new \ArrayIterator($this->namedRoutes);
    }

    /**
     * 获取
     * @return string
     */
    public function getActionUrl() {

    }

    public function getActionRoutes() {

    }

}
