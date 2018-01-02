<?php
namespace Think\Session\Driver;

class Redis
{
    private $handler;
    private $get_result;

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function open($savePath, $sessName)
    {
        $default_options = [
            'host'       => '127.0.0.1',
            'port'       => 6379,
            'auth'       => false,
            'expire'     => (int) ini_get('session.gc_maxlifetime'),
            'prefix'     => C('SESSION_PREFIX'),
            'timeout'    => false,
            'persistent' => false,
        ];
        $config_options = C('SESSION_REDIS');
        $this->options = $config_options ? array_merge($default_options, $config_options) : $default_options;

        if (!extension_loaded('redis')) {
            E(L('_NOT_SUPPERT_') . ':redis');
        }
        $this->handler = new \Redis;
        return true;
    }

    /**
     * 连接Redis服务端
     * @access public
     * @param bool $is_master : 是否连接主服务器
     */
    public function connect()
    {
        $func = $this->options['persistent'] ? 'pconnect' : 'connect';

        if ($this->options['timeout'] === false) {
            $result = $this->handler->$func($this->options['host'], $this->options['port']);
            if (!$result) {
                throw new \Think\Exception('Redis Error', 100);
            }
        } else {
            $result = $this->handler->$func($this->options['host'], $this->options['port'], $this->options['timeout']);
            if (!$result) {
                throw new \Think\Exception('Redis Error', 101);
            }
        }
        if ($this->options['auth']) {
            $result = $this->handler->auth($this->options['auth']);
            if (!$result) {
                throw new \Think\Exception('Redis Error', 102);
            }
        }
    }

    /**
     * 关闭Session
     * @access public
     */
    public function close()
    {
        if ($this->options['persistent'] == 'pconnect') {
            $this->handler->close();
        }
        return true;
    }

    /**
     * 读取Session
     * @access public
     * @param string $sessID
     */
    public function read($sessID)
    {
        $this->connect(0);
        $this->get_result = $this->handler->get($this->options['prefix'] . $sessID);
        return (string) $this->get_result;
    }

    /**
     * 写入Session
     * @access public
     * @param string $sessID
     * @param String $sessData
     */
    public function write($sessID, $sessData)
    {
        if (!$sessData || $sessData == $this->get_result) {
            return true;
        }
        $this->connect(1);
        $expire = $this->options['expire'];
        $sessID = $this->options['prefix'] . $sessID;
        if (is_int($expire) && $expire > 0) {
            $result = $this->handler->setex($sessID, $expire, $sessData);
            $re     = $result ? 'true' : 'false';
        } else {
            $result = $this->handler->set($sessID, $sessData);
            $re     = $result ? 'true' : 'false';
        }
        return $result;
    }

    /**
     * 删除Session
     * @access public
     * @param string $sessID
     */
    public function destroy($sessID)
    {
        $this->connect(1);
        return $this->handler->delete($this->options['prefix'] . $sessID);
    }

    /**
     * Session 垃圾回收
     * @access public
     * @param string $sessMaxLifeTime
     */
    public function gc($sessMaxLifeTime)
    {
        return true;
    }

    /**
     * 打开Session
     * @access public
     * @param string $savePath
     * @param mixed $sessName
     */
    public function execute()
    {
        session_set_save_handler(
            [&$this, 'open'],
            [&$this, 'close'],
            [&$this, 'read'],
            [&$this, 'write'],
            [&$this, 'destroy'],
            [&$this, 'gc']
        );
    }

    public function __destruct()
    {
        if ($this->options['persistent'] == 'pconnect') {
            $this->handler->close();
        }
        session_write_close();
    }
}
