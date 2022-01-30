<?php

namespace app\adminapi\controller;

use app\adminapi\Controller;

class Common extends Controller
{
    /**
     * 当前应用下的所有路由
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021/12/30
     */
    public function routes()
    {
        $routes = $this->app->route->getRuleList();
        $routes = array_filter($routes, function ($e) {
            return is_string($e['route']) && !empty($e['route']);
        });
        $routes = array_column($routes, 'route');
        trace('获取路由表','op');
        return $this->success($routes);
    }
}