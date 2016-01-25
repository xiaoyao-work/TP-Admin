<?php
namespace Lib;

/**
 *  redis操作类
 *  @author 逍遥·李志亮 <xiaoyao.working@gmail.com>
 */
class RedisHandler {
    /**
     * @var Redis $redis
     */
    private $redis;
    private static $_instance = array();

    private function __construct($config) {
        if($config == 'REDIS_DEFAULT'){
            $conf['server'] = C('REDIS_HOST');
            $conf['port'] = C('REDIS_PORT');
        }else{
            $conf = C($config);
        }
        $this->redis = new Redis();
        try{
            $this->redis->connect($conf['server'], $conf['port']);
            $this->redis->ping();
        }catch (Exception $e){
            throw_exception("RedisHandle_redis_connect 3 ".$e->getMessage());
        }
        return $this->redis;
    }

    /**
     * 取得handle对象
     * $config = array(
     *  'server' => '127.0.0.1' 服务器
     *  'port'   => '6379' 端口号
     * )
     * @param string $config
     * @return RedisHandle
     */
    public static function getInstance($config = 'REDIS_DEFAULT') {
        if (!(self::$_instance[$config] instanceof self)) {
            self::$_instance[$config] = new self ($config);
        }
        return self::$_instance[$config];
    }


    /**
     * 设置值(string)会将$value自动转为json格式
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param int $timeOut 时间
     * @return bool
     */
    public function setJson($key, $value, $timeOut = 0) {
        $value = json_encode($value);
        $retRes = $this->redis->set($key, $value);
        if ($timeOut > 0) $this->redis->setTimeout($key, $timeOut);
        return $retRes;
    }

    /**
     * 通过KEY获取数据(string),返回array
     * @param string $key KEY名称
     * @return mixed
     */
    public function getJson($key) {
        $result = $this->redis->get($key);
        return json_decode($result, true);
    }

    /**
     * 双重缓存，防止击穿 (如果key没有被初始化，仍有可能会导致击穿现象)
     * @param int  $key    Redis key
     * @return Mix
     */
    public function getByLock($key) {
        $sth = $this->redis->get($key);
        if ($sth === false) {
            return $sth;
        } else {
            $sth = json_decode($sth, true);
            if (intval($sth['expire']) <= time()) {
                $lock = $this->redis->incr($key . ".lock");
                if ($lock === 1) {
                    return false;
                }
                return $sth['data'];
            } else {
                return $sth['data'];
            }
        }
    }

    /**
     * 设置Redis，防止缓存击穿
     * @param int  $key    Redis key
     * @param Mix  $value  缓存值
     * @param int  $expire 过期时间
     * @return bool
     */
    public function setByLock($key, $value, $expire=0) {
        $expire = intval($expire);
        if ($expire > 0) {
            $r_exp = $expire + 100;
            $c_exp = time() + $expire;
        } else {
            $expire = 300;
            $r_exp = $expire + 100;
            $c_exp = time() + $expire;
        }
        $arg = array("data" => $value, "expire" => $c_exp);
        $rs = $this->redis->setex($key, $r_exp, json_encode($arg, true));
        $this->redis->del($key . ".lock");
        return $rs;
    }

    /**
     * 清空数据
     */
    public function flushAll() {
        return true;
        //return $this->redis->flushAll();
    }

    /**
     * 数据入队列(对应redis的list数据结构)
     * @param string $key KEY名称
     * @param string|array $value 需压入的数据
     * @param bool $right 是否从右边开始入
     * @return int
     */
    public function push($key, $value) {
        $value = json_encode($value);
        return $this->redis->lPush($key, $value);
    }

    /**
     * 数据出队列（对应redis的list数据结构）
     * @param string $key KEY名称
     * @param bool $left 是否从左边开始出数据
     * @return mixed
     */
    public function pop($key) {
        $val = $this->redis->rPop($key);
        return json_decode($val);
    }

    /**
     * 透明地调用redis其它操作方法
     *
     * @param string $name
     * @param array $params
     * @return mixed
     */
    public function __call($name, $params) {
        return call_user_method_array($name, $this->redis, $params);
    }
}