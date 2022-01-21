<?php
declare (strict_types=1);

namespace app\adminapi\middleware;

use app\model\system\SystemAdminModel;
use Closure;
use plum\exception\AuthException;
use plum\utils\Token;

/**
 * 校验是否登录
 */
class AuthMiddleware
{
    public function handle( $request, Closure $next)
    {
        //判断是否登录
        $adminId = Token::auth(app('http')->getName());
        $admin = SystemAdminModel::find($adminId);
        if (!$admin) {
            throw new AuthException();
        }
        $request->userinfo = $admin;
        return $next($request);
    }
}
