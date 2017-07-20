<?php
namespace Lib;

use Exception;

/**
 * 并发CURL类库
 * @author 逍遥·李志亮 <xiaoyao.working@gmail.com>
 */
class ConcurrenceCurl {
	const TIMEOUT_MS  = 1000;
	static $inst      = null;
	static $singleton = 0;
	private $mc;
	private $msgs;
	private $running;
	private $execStatus;
	private $selectStatus;
	private $sleepIncrement = 1.1;
	private $requests       = [];
	private $responses      = [];
	private $properties     = [];
	private $curlExecTime   = [];
	public function __construct() {
		if (self::$singleton == 0) {
			throw new Exception('This class cannot be instantiated by the new keyword.  You must instantiate it using: $obj = ConcurrenceCurl::getInstance();');
		}
		$this->mc         = curl_multi_init();
		$this->properties = [
			'code'   => CURLINFO_HTTP_CODE,
			'time'   => CURLINFO_TOTAL_TIME,
			'length' => CURLINFO_CONTENT_LENGTH_DOWNLOAD,
			'type'   => CURLINFO_CONTENT_TYPE,
			'url'    => CURLINFO_EFFECTIVE_URL,
		];
	}

	// simplifies example and allows for additional curl options to be passed in via array
	public function addURL($url, $options = []) {
		$ch = curl_init($url);
		// 记录CURL开始时间
		// $this->curlExecTime[$this->getKey($ch)] = microtime(true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		foreach ($options as $option => $value) {
			curl_setopt($ch, $option, $value);
		}
		return $this->addCurl($ch);
	}

	public function addCurl($ch) {
		$key                  = $this->getKey($ch);
		$this->requests[$key] = $ch;
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, [$this, 'headerCallback']);
		$code = curl_multi_add_handle($this->mc, $ch);
		if ($code === CURLM_OK || $code === CURLM_CALL_MULTI_PERFORM) {
			do {
				$code = $this->execStatus = curl_multi_exec($this->mc, $this->running);
			} while ($this->execStatus === CURLM_CALL_MULTI_PERFORM);
			return new ConcurrenceCurlManager($key);
		} else {
			return $code;
		}
	}

	public function getResult($key = null) {
		if ($key != null) {
			if (isset($this->responses[$key]['data'])) {
				return $this->responses[$key];
			}
			$innerSleepInt = $outerSleepInt = 100;

			// 超时判断

			while ($this->running && ($this->execStatus == CURLM_OK || $this->execStatus == CURLM_CALL_MULTI_PERFORM)) {
				usleep($outerSleepInt);
				// $outerSleepInt *= $this->sleepIncrement;
				$ms = curl_multi_select($this->mc);
				if ($ms >= CURLM_CALL_MULTI_PERFORM) {
					do {
						$this->execStatus = curl_multi_exec($this->mc, $this->running);
						usleep($innerSleepInt);
						// $innerSleepInt *= $this->sleepIncrement;
					} while ($this->execStatus == CURLM_CALL_MULTI_PERFORM);
					$innerSleepInt = 100;
				}
				$this->storeResponses();
				if (isset($this->responses[$key]['data'])) {
					return $this->responses[$key];
				}
				$runningCurrent = $this->running;
			}
			return null;
		}
		return false;
	}

	public function cleanupResponses() {
		$this->responses = [];
	}

	private function getKey($ch) {
		return (string) $ch;
	}

	private function headerCallback($ch, $header) {
		$_header  = trim($header);
		$colonPos = strpos($_header, ':');
		if ($colonPos > 0) {
			$key                                                  = substr($_header, 0, $colonPos);
			$val                                                  = preg_replace('/^\W+/', '', substr($_header, $colonPos));
			$this->responses[$this->getKey($ch)]['headers'][$key] = $val;
		}
		return strlen($header);
	}

	private function storeResponses() {
		while ($done = curl_multi_info_read($this->mc)) {
			$key                           = (string) $done['handle'];
			$this->responses[$key]['data'] = curl_multi_getcontent($done['handle']);
			foreach ($this->properties as $name => $const) {
				$this->responses[$key][$name] = curl_getinfo($done['handle'], $const);
			}
			curl_multi_remove_handle($this->mc, $done['handle']);
			curl_close($done['handle']);
		}
	}

	public static function getInstance() {
		if (self::$inst == null) {
			self::$singleton = 1;
			self::$inst      = new self();
		}
		return self::$inst;
	}

	public function __destruct() {
		curl_multi_close($this->mc);
	}

	public static function getCurl($url, $timeout = 30, $header = []) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$data                = curl_exec($ch);
		list($header, $data) = explode("\r\n\r\n", $data);
		$http_code           = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 301 || $http_code == 302) {
			$matches = [];
			preg_match('/Location:(.*?)\n/', $header, $matches);
			$url = trim(array_pop($matches));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$data = curl_exec($ch);
		}
		if ($data == false) {
			curl_close($ch);
		}
		@curl_close($ch);
		return $data;
	}

	public static function postCurl($url, $params = [], $timeout = 30, $header = []) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, true);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		if (!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		$data                = curl_exec($ch);
		list($header, $data) = explode("\r\n\r\n", $data);
		$http_code           = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($http_code == 301 || $http_code == 302) {
			$matches = [];
			preg_match('/Location:(.*?)\n/', $header, $matches);
			$url = trim(array_pop($matches));
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HEADER, false);
			$data = curl_exec($ch);
		}
		if ($data == false) {
			curl_close($ch);
		}
		@curl_close($ch);
		return $data;
	}

}

/**
 * ConcurrenceCurl 操作管理
 */
class ConcurrenceCurlManager {
	private $key;
	private $concurrenceCurl;
	public function __construct($key) {
		$this->key             = $key;
		$this->concurrenceCurl = ConcurrenceCurl::getInstance();
	}

	public function getResponse() {
		return $this->concurrenceCurl->getResult($this->key);
	}

	public function __get($name) {
		$responses = $this->concurrenceCurl->getResult($this->key);
		return $responses[$name];
	}

	public function __isset($name) {
		$val = self::__get($name);
		return empty($val);
	}
}
