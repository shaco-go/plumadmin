<?php

namespace app\model\system;

use plum\basic\BaseModel;

class SystemDebugLogModel extends BaseModel
{
    protected $table = 'system_debug_log';

    /**
     * 搜索器 - IP
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function searchIpAttr($query, $value)
    {
        $query->whereLike('ip', "%{$value}%");
    }

    /**
     * 搜索器 - URL
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function searchUrlAttr($query, $value)
    {
        $query->whereLike('ip', "%{$value}%");
    }

    /**
     * 搜索器 - time
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public function searchTimeAttr($query, $value)
    {
        if (count($value) === 2 && strtotime($value[0]) && strtotime($value[1])) {
            $value[1] = $value[1] . ' 23:59:59';
            $query->whereTime('create_time', 'between', $value);
        }
    }
}