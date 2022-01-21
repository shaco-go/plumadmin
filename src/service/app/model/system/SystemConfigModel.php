<?php

namespace app\model\system;

use plum\basic\BaseModel;
use plum\exception\FailException;
use plum\utils\Arr;

class SystemConfigModel extends BaseModel
{
    protected $name = 'system_config';
    protected $type = ['value' => 'json'];

    const ALLOW_FIELDS = [
        'filesystem',
        'filesystem_valid',
        'email'
    ];

    /**
     * 设置配置数据s
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function setItem($key, $value)
    {
        if (!in_array($key, self::ALLOW_FIELDS)) {
            throw new FailException('设置配置失败');
        }
        $exists = self::where('key', $key)->find();
        if ($exists) {
            $exists->value = $value;
            $exists->save();
        } else {
            self::create(compact('key', 'value'));
        }
        self::cacheClear();
    }

    /**
     * 获取配置数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getItem($key)
    {
        if (!in_array($key, self::ALLOW_FIELDS)) {
            throw new FailException('获取配置失败');
        }
        $data = self::getAll()[$key] ?? [];
        $originalConfig = config($key);
        if (!config($key)) {
            throw new FailException('获取配置失败');
        }
        return Arr::mergeMultiple($originalConfig, $data);
    }

    /**
     * 获取所有的数据库数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getDatabaseAll()
    {
        try {
            return self::select()->toArray();
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * 获取所有的数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getAll()
    {
        $key = env('cache.prefix') . "_system_config";
        //获取缓存数据
        if (!$value = cache($key)) {
            $value = self::getDatabaseAll();
            //保存两小时
            cache($key, $value, 7200);
        }
        return array_column($value,'value','key');
    }

    /**
     * 清除
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function cacheClear()
    {
        $key = env('cache.prefix') . "_system_config";
        cache($key, null);
    }
}