<?php
namespace Lib;
use Exception;

/**
 * 异步CURL类库
 * @author 李志亮 <lizhiliang@kankan.com>
 */

class EpiCurl {
    const TIMEOUT_MS = 1000;
    static $inst = null;
    static $singleton = 0;
    private $mc;
    private $msgs;
    private $running;
    private $execStatus;
    private $selectStatus;
    private $sleepIncrement = 1.1;
    private $requests = array();
    private $responses = array();
    private $properties = array();
    private $curlExecTime = array();
    function __construct() {
        if(self::$singleton == 0) {
            throw new Exception('This class cannot be instantiated by the new keyword.  You must instantiate it using: $obj = EpiCurl::getInstance();');
        }
        $this->mc = curl_multi_init();
        $this->properties = array(
            'code'  => CURLINFO_HTTP_CODE,
            'time'  => CURLINFO_TOTAL_TIME,
            'length'=> CURLINFO_CONTENT_LENGTH_DOWNLOAD,
            'type'  => CURLINFO_CONTENT_TYPE,
            'url'   => CURLINFO_EFFECTIVE_URL
            );
    }

    // simplifies example and allows for additional curl options to be passed in via array
    public function addURL($url,$options=array()) {
        $ch = curl_init($url);
        // 记录CURL开始时间
        // $this->curlExecTime[$this->getKey($ch)] = microtime(true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        foreach($options as $option=>$value) {
            curl_setopt($ch, $option, $value);
        }
        return $this->addCurl($ch);
    }

    public function addCurl($ch) {
        $key = $this->getKey($ch);
        $this->requests[$key] = $ch;
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this, 'headerCallback'));
        $code = curl_multi_add_handle($this->mc, $ch);
        if($code === CURLM_OK || $code === CURLM_CALL_MULTI_PERFORM) {
            do {
                $code = $this->execStatus = curl_multi_exec($this->mc, $this->running);
            } while ($this->execStatus === CURLM_CALL_MULTI_PERFORM);
            return new EpiCurlManager($key);
        } else {
            return $code;
        }
    }

    public function getResult($key = null) {
        if($key != null) {
            if(isset($this->responses[$key]['data'])) {
                return $this->responses[$key];
            }
            $innerSleepInt = $outerSleepInt = 100;

            // 超时判断

            while($this->running && ($this->execStatus == CURLM_OK || $this->execStatus == CURLM_CALL_MULTI_PERFORM))
            {
                usleep($outerSleepInt);
                // $outerSleepInt *= $this->sleepIncrement;
                $ms=curl_multi_select($this->mc);
                if($ms >= CURLM_CALL_MULTI_PERFORM)
                {
                    do{
                        $this->execStatus = curl_multi_exec($this->mc, $this->running);
                        usleep($innerSleepInt);
                        // $innerSleepInt *= $this->sleepIncrement;
                    }while($this->execStatus==CURLM_CALL_MULTI_PERFORM);
                    $innerSleepInt = 100;
                }
                $this->storeResponses();
                if(isset($this->responses[$key]['data']))
                {
                    return $this->responses[$key];
                }
                $runningCurrent = $this->running;
            }
            return null;
        }
        return false;
    }

    public function cleanupResponses() {
        $this->responses = array();
    }

    private function getKey($ch) {
        return (string)$ch;
    }

    private function headerCallback($ch, $header) {
        $_header = trim($header);
        $colonPos= strpos($_header, ':');
        if($colonPos > 0)
        {
            $key = substr($_header, 0, $colonPos);
            $val = preg_replace('/^\W+/','',substr($_header, $colonPos));
            $this->responses[$this->getKey($ch)]['headers'][$key] = $val;
        }
        return strlen($header);
    }

    private function storeResponses() {
        while($done = curl_multi_info_read($this->mc))
        {
            $key = (string)$done['handle'];
            $this->responses[$key]['data'] = curl_multi_getcontent($done['handle']);
            foreach($this->properties as $name => $const)
            {
                $this->responses[$key][$name] = curl_getinfo($done['handle'], $const);
            }
            curl_multi_remove_handle($this->mc, $done['handle']);
            curl_close($done['handle']);
        }
    }

    static function getInstance() {
        if(self::$inst == null) {
            self::$singleton = 1;
            self::$inst = new EpiCurl();
        }
        return self::$inst;
    }

    function __destruct() {
        curl_multi_close($this->mc);
    }
}

/**
 * EpiCurl 操作管理
 */
class EpiCurlManager {
    private $key;
    private $epiCurl;
    function __construct($key) {
        $this->key = $key;
        $this->epiCurl = EpiCurl::getInstance();
    }

    public function getResponse() {
        return $this->epiCurl->getResult($this->key);
    }

    function __get($name) {
        $responses = $this->epiCurl->getResult($this->key);
        return $responses[$name];
    }

    function __isset($name) {
        $val = self::__get($name);
        return empty($val);
    }
}