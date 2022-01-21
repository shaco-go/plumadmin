<?php

namespace app\adminapi\middleware;

use plum\exception\PermissionException;
use plum\utils\Arr;

/**
 * 校验权限
 */
class PermissionMiddleware
{
    public function handle( $request,\Closure $next)
    {
        if(!$request->userinfo['is_super']){
            //非管理员
            $rules = $request->userinfo->getAllRule();
            //获取所有的子权限
            $methods = Arr::collapse(array_column($rules,'method'));
            //获取当前的路由
            $route = $request->rule()->getRoute();
            if(!Arr::inArray($route,$methods)){
                throw new PermissionException();
            }
        }
        return $next($request);
    }
}