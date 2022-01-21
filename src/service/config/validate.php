<?php

use plum\utils\Arr;

return [

    // 二位数组进行校验值
    'arrayColumn' => function ($value, $rule, $data, $field, $name) {
        // //必须是二维数组
        if (!is_array($value) || Arr::isAssoc($value)) {
            return "{$name}格式错误";
        }
        $validate = \think\facade\Validate::rule($rule);
        foreach ($value as $item) {
            if (!$validate->check($item)) {
                return $validate->getError();
            }
        }
        return true;
    },

    //若值存在则需要校验
    'requireIn'   => function ($value, $rule, $data, $field, $name) {
        $rules = explode(',', $rule);
        $key = array_shift($rules);
        if (!isset($data[$key])) {
            return "{$key}不能为空";
        }
        if ($rules === false || count($rules) < 1) {
            return "{$name}格式错误";
        }
        $rules = array_map(function ($item) {
            if ($item === 'true') {
                $item = true;
            } elseif ($item === 'false') {
                $item = false;
            }
            return $item;
        }, $rules);
        if (in_array($data[$key], $rules) && ($value===null || $value==='')) {
            return "{$name}不能为空";
        }
        return true;
    }
];
