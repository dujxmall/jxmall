<?php
/**
 * Created by PhpStorm.
 * User: ganxi
 * Date: 2022/4/14
 * Time: 10:33
 * Note:
 */

namespace app\core;

class Logger extends \yii\log\Logger
{
    public function log($message, $level, $category = 'application')
    {

        $time = microtime(true);
        $traces = [];
        if ($this->traceLevel > 0) {
            $count = 0;
            $ts = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
            array_pop($ts);
            array_pop($ts); // remove the last trace since it would be the entry script, not very useful
            foreach ($ts as $i => $trace) {
                if (isset($trace['file'], $trace['line']) && strpos($trace['file'], YII2_PATH) !== 0) {
                    unset($trace['object'], $trace['args']);
                    $traces[] = $trace;
                    if (++$count >= $this->traceLevel) {
                        break;
                    }
                }
            }
        }
        $data = [$message, $level, $category, $time, $traces, memory_get_usage()];
        if ($this->profilingAware && in_array($level, [self::LEVEL_PROFILE_BEGIN, self::LEVEL_PROFILE_END])) {
            $this->messages[($level == self::LEVEL_PROFILE_BEGIN ? 'begin-' : 'end-') . md5(json_encode($message))] = $data;
        } else {
            $this->messages[] = $data;
        }

        if ($this->flushInterval > 0 && count($this->messages) >= $this->flushInterval) {
            $this->flush();
        }
    }
}