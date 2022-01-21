<?php

namespace plum\core;

use plum\utils\Str;
use think\Paginator;

class Query extends \think\db\Query
{
    /**
     * 扩展分页,占用size
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 19:53
     */
    public function paginate($listRows = null, $simple = false): Paginator
    {
        //重写每页页数
        if (!$listRows) {
            $listRows = input('size', 15);
        }
        return parent::paginate($listRows, $simple);
    }

    /**
     * 自动搜索
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 20:54
     */
    public function autoSearch(...$args): Query
    {
        $params = empty($params) ? request()->param() : $params;
        if (empty($params)) {
            return $this;
        }
        //允许排序的字段
        $args = array_map(function ($item) {
            return Str::studly($item);
        }, $args);
        foreach ($params as $field => $value) {
            if (count($args) > 0 && !in_array(Str::studly($field), $args)) {
                continue;
            }
            $method = 'search' . Str::studly($field) . 'Attr';
            //只要不是null,空字符,空数组,就可以进行搜索
            if ($value !== null && $value !== '' && $value !== [] && method_exists($this->model, $method)) {
                $this->model->$method($this, $value, $params);
            }
        }
        return $this;
    }

    /**
     * 根据post,get参数进行自动排序
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 20:53
     */
    public function autoOrder(...$args): Query
    {
        $sort = input('sortby');
        $order = input('order');
        $tableFields = array_keys($this->getFields());
        //允许排序的字段
        if (count($args) > 0) {
            $allowFields = $args;
        } else {
            $allowFields = $tableFields;
        }

        if ($sort && $order && in_array($sort, $allowFields) && in_array($order, ['asc', 'desc'])) {
            //自主排序
            $this->order($sort, $order);
        } else {
            //默认排序,sort升序,时间降序,id降序
            if (in_array('sort', $tableFields)) {
                $this->order("sort", 'asc');
            } elseif (in_array('create_time', $tableFields)) {
                $this->order("create_time", 'desc');
            } else {
                $this->order("id", 'desc');
            }
        }
        return $this;
    }
}