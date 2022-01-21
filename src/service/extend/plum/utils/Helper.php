<?php

namespace plum\utils;

class Helper
{
    /**
     * 获取子ID
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getChildrenIds($model, $ids, $contain = true)
    {
        if (empty($ids))
            return [];
        if(!is_array($ids))
            $ids = [$ids];
        $childrenIds = $model->whereIn('parent_id', $ids)->column('id');
        if (!empty($childrenIds)) {
            $childrenIds = array_merge($childrenIds, self::getChildrenIds($model, $childrenIds));
        }
        if ($contain) {
            //是否包含父级ID
            $childrenIds = array_merge($childrenIds, $ids);
        }
        return $childrenIds;
    }

    /**
     * 美化文件大小
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function prettyFileSize($size)
    {
        if ($size < 1024) {
            return $size . 'B';
        } elseif ($size < 1024 * 1024) {
            return round(bcdiv($size, 1024, 2), 2) . 'KB';
        } elseif ($size < 1024 * 1024 * 1024) {
            return round(bcdiv($size, 1024 * 1024, 2), 2) . 'MB';
        } else {
            return round(bcdiv($size, 1024 * 1024 * 1024, 2), 2) . 'GB';
        }
    }

    /**
     * 判断是不是linux系统
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/17
     */
    public static function isLinux(): bool
    {
        return DIRECTORY_SEPARATOR !== '\\';
    }

}