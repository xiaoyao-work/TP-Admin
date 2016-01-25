<?php
namespace Lib;
use Think\Log as SLog;

/**
* 日志处理类
* @author 逍遥·李志亮 <xiaoyao.working@gmail.com>
*/
class Log {

    // 日志级别 从上到下，由低到高
    const EMERG     = 'EMERG';  // 严重错误: 导致系统崩溃无法使用
    const ALERT     = 'ALERT';  // 警戒性错误: 必须被立即修改的错误
    const CRIT      = 'CRIT';  // 临界值错误: 超过临界值的错误，例如一天24小时，而输入的是25小时这样
    const ERR       = 'ERR';  // 一般错误: 一般性错误
    const WARN      = 'WARN';  // 警告性错误: 需要发出警告的错误
    const NOTICE    = 'NOTIC';  // 通知: 程序可以运行但是还不够完美的错误
    const INFO      = 'INFO';  // 信息: 程序输出信息
    const DEBUG     = 'DEBUG';  // 调试: 调试信息
    const SQL       = 'SQL';  // SQL：SQL语句 注意只在调试模式开启时有效

    public static function info($message, $destination='') {
        self::write($message, self::INFO, $destination);
    }

    public static function notice($message, $destination='') {
        self::write($message, self::NOTICE, $destination);
    }

    public static function warn($message, $destination='') {
        self::write($message, self::WARN, $destination);
    }

    public static function error($message, $destination='') {
        self::write($message, self::ERR, $destination);
    }

    /**
     * 写入日志到文件
     * @static
     * @access protected
     * @param string $message 日志信息
     * @param string $level  日志级别
     * @param string $destination  写入目标
     * @return void
     */
    protected static function write($message, $level=self::ERR, $destination='') {
        $type = empty($type) ? C('LOG_TYPE') : $type;
        $destination = empty($destination) ? C('LOG_PATH') . $level . '/' . date('y_m_d').'.log' : $destination;
        SLog::write(json_encode($message), $level, 'file', $destination);
    }
}