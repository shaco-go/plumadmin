<?php

namespace plum\utils;

use app\model\system\SystemAttachmentModel;

class Attachment
{
    /**
     * 获取图片的id
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getId(&$data)
    {
        if (empty($data)) {
            if (isset($data)) {
                $data = is_array($data) ? [] : 0;
            }
        } else {
            if (Arr::isAssoc($data)) {
                //关联数组
                $data = $data['id'];
            } else {
                //索引数组,多图形式
                $data = array_map(function ($item) {
                    return $item['id'];
                }, $data);
            }
        }
    }

    /**
     * 根据id获取图片
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/29
     */
    public static function getItem(&$data, $fields)
    {
        $array = Arr::toArr($data);
        //一位数组转化成二维数组
        if (Arr::isAssoc($array)) {
            $array = [$array];
        }
        //获取所有的ids
        $ids = [];
        foreach ($array as $item) {
            foreach ($item as $field => $v) {
                if (in_array($field, $fields)) {
                    if (is_array($v)) {
                        $ids = array_merge($ids, $v);
                    } else {
                        $ids[] = $v;
                    }

                }
            }
        }
        //获取文件列表
        $files = SystemAttachmentModel::whereIn('id', $ids)
            ->append(['url'])
            ->visible(['id', 'name'])
            ->select()
            ->toArray();
        //将id提出
        $files = array_column($files, null, 'id');
        if (Arr::isAssoc(Arr::toArr($data))) {
            //一维数组的情况下
            foreach ($array[0] as $field => $itemIds) {
                if (in_array($field, $fields)) {
                    $data[$field] = self::setKeyImage($itemIds, $files);
                }
            }
        } else {
            foreach ($array as $key => $item) {
                foreach ($item as $field => $itemIds) {
                    if (in_array($field, $fields)) {
                        $data[$key][$field] = self::setKeyImage($itemIds, $files);
                    }
                }
            }
        }
    }

    /**
     * 根据id获取图片item
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/27
     */
    private static function setKeyImage($key, $array)
    {
        if (is_array($key)) {
            return array_filter(array_map(function ($item) use ($array) {
                return $array[$item] ?? null;
            }, $key), function ($item) {
                return !!$item;
            });
        }
        return $array[$key] ?? null;
    }
}