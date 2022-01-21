<?php

namespace plum\traits;

trait ResponseTrait
{
    /**
     * 响应数据
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 19:34
     */
    public function success(...$arg): \think\response\Json
    {
        $code = 200;
        $data = [];
        $message = 'success';
        if (isset($arg[0])) {
            if (is_string($arg[0])) {
                $message = $arg[0];
            } else {
                $data = $arg[0];
            }
        }
        if (isset($arg[1])) {
            if (is_string($arg[1])) {
                $message = $arg[1];
            } else {
                $data = $arg[1];
            }
        }
        return json(compact('code', 'data', 'message'));
    }
}
