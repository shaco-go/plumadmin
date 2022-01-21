<?php

namespace plum\basic;

use think\facade\Log;

abstract class BaseTimer
{
    private $expireTime;
    private $currentTime;

    /**
     * 每秒
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    protected function setSecond($second)
    {
        $this->expireTime = time() + $second;
        $this->currentTime = time();
    }

    /**
     * 每小时
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    protected function setEveryHour($minute)
    {
        $this->expireTime = mktime(date('H') + 1, $minute, 0);
        $this->currentTime = mktime(date('H'), $minute, 0);
    }

    /**
     * 每天
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    protected function setEveryDay($hour, $minute)
    {
        $this->expireTime = mktime($hour, $minute, 0, date('m'), date('d') + 1);
        $this->currentTime = mktime($hour, $minute, 0, date('m'), date('d'));
    }

    /**
     * 每周
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    protected function setEveryWeek($week, $hour, $minute)
    {
        $this->expireTime = mktime($hour, $minute, 0, date('m'), date('d') - date('N') + $week + 7);
        $this->currentTime = mktime($hour, $minute, 0, date('m'), date('d') - date('N') + $week);
    }

    /**
     * 每月
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    protected function setEveryMonth($day, $hour, $minute)
    {
        $this->expireTime = mktime($hour, $minute, 0, date('m') + 1, $day);
        $this->currentTime = mktime($hour, $minute, 0, date('m'), $day);
    }

    /**
     * 执行任务
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月15日 16:53
     */
    public function execute()
    {
        try {
            //执行时间
            $this->timer();
            //执行任务,且上锁,写入日志
            if ($this->currentTime && $this->expireTime && time() >= $this->currentTime && !$this->isLock()) {
                $this->handler();
                $this->lock();
            }
        } catch (\Throwable $e) {
            Log::record([
                'message'=>'定时器错误:'.$e->getMessage(),
                'file'=>$e->getFile()." [".$e->getLine()."]",
                'trace'=>$e->getTraceAsString()
            ],'error');
        }
    }

    /**
     * lock
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月15日 16:28
     */
    private function lock()
    {
        cache($this->getKey(), $this->expireTime);
    }

    /**
     * 是否上锁
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月15日 16:28
     */
    private function isLock()
    {
        $expireTime = cache($this->getKey());
        if (!$expireTime || time() >= $expireTime) {
            return false;
        }
        return true;
    }

    /**
     * 获取键值
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月15日 16:28
     */
    private function getKey()
    {
        $name = $this->name();
        return env('cache.prefix','').'_'."timer_{$name}_lock";
    }

    abstract public function handler();

    abstract public function name(): string;

    abstract public function timer();
}