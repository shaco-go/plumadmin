<?php

namespace plum\basic;

use think\facade\Cache;

abstract class BaseQueue
{
    abstract public function handle($data);

    abstract public function name(): string;

    public static function send($data, $delay = 0)
    {
        $redis = Cache::store('redis')->handler();
        $queue_waiting =  '_queue-delayed';
        $queue_delay = '_queue-failed';
        $now = time();
        $package_str = json_encode([
            'id'       => rand(),
            'time'     => $now,
            'delay'    => 0,
            'attempts' => 0,
            'queue'    => (new static)->name(),
            'data'     => $data
        ]);
        if ($delay) {
            return $redis->zAdd($queue_delay, $now + $delay, $package_str);
        }
        return $redis->lPush($queue_waiting . (new static)->name(), $package_str);
    }
}