<?php
namespace Think\Http;

class Cookies extends \Think\Helper\Set {
    /**
     * Default cookie settings
     * @var array
     */
    protected $defaults = [
        'value'    => '',
        'domain'   => null,
        'path'     => null,
        'expires'  => null,
        'secure'   => false,
        'httponly' => false,
    ];

    /**
     * Set cookie
     *
     * The second argument may be a single scalar value, in which case
     * it will be merged with the default settings and considered the `value`
     * of the merged result.
     *
     * The second argument may also be an array containing any or all of
     * the keys shown in the default settings above. This array will be
     * merged with the defaults shown above.
     *
     * @param string $key   Cookie name
     * @param mixed  $value Cookie settings
     */
    public function set($key, $value) {
        if (is_array($value)) {
            $cookieSettings = array_replace($this->defaults, $value);
        } else {
            $cookieSettings = array_replace($this->defaults, ['value' => $value]);
        }
        parent::set($key, $cookieSettings);
    }

    /**
     * Remove cookie
     *
     * Unlike \Slim\Helper\Set, this will actually *set* a cookie with
     * an expiration date in the past. This expiration date will force
     * the client-side cache to remove its cookie with the given name
     * and settings.
     *
     * @param string $key      Cookie name
     * @param array  $settings Optional cookie settings
     */
    public function remove($key, $settings = []) {
        $settings['value']   = '';
        $settings['expires'] = time() - 86400;
        $this->set($key, array_replace($this->defaults, $settings));
    }
}
