<?php

namespace plum\utils;

use plum\exception\AuthException;

class Token
{
    /**
     * 生成token
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:33
     */
    public static function build($uuid, $channel): array
    {
        $token = self::generateToken();
        $refreshToken = self::generateToken();
        $expire = env('token.expire', 7200) + time();
        $refreshExpire = env('token.refresh_expire', 604800) + time();
        $key = self::getKey($token, $channel);
        $value = [
            'uuid'          => $uuid,
            'expire'        => $expire,
            'refresh_token' => $refreshToken
        ];
        cache($key, $value, $refreshExpire);
        return [
            'token'          => $token,
            'expire'         => $expire,
            'refresh_token'  => $refreshToken,
            'refresh_expire' => $refreshExpire,
        ];
    }

    /**
     * 生成键值
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:35
     */
    private static function getKey($token, $channel): string
    {
        return "token_{$channel}_{$token}";
    }

    /**
     * 拉黑
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月04日 18:14
     */
    public static function invalid($channel)
    {
        $token = self::getToken();
        if($token){
            cache(self::getKey($token, $channel), null);
        }
    }

    /**
     * 刷新token
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:47
     */
    public static function refresh($refreshToken, $channel = null): array
    {
        if ($token = self::getToken()) {
            if ($value = cache(self::getKey($token, $channel))) {
                if ($refreshToken === $value['refresh_token']) {
                    //作废掉之前的token
                    cache(self::getKey($token, $channel));
                    //生成新的
                    return self::build($value['uuid'], $channel);
                }
            }
        }
        throw new AuthException();
    }

    /**
     * 验证获取uuid
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:44
     */
    public static function auth($channel = null, $force = true)
    {
        $channel = $channel ?? app('http')->getName();
        if ($token = self::getToken()) {
            if ($value = cache(self::getKey($token, $channel))) {
                if ($value['expire'] >= time()) {
                    return $value['uuid'];
                }
            }
        }
        if ($force) {
            //抛出异常
            throw new AuthException();
        }
        return 0;
    }

    /**
     * 获取token
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:12
     */
    private static function getToken()
    {
        $authorization = request()->header('authorization', '');
        $pattern = '/^bearer\s?(\w{32})$/i';
        if (preg_match($pattern, $authorization, $match) && isset($match[1])) {
            return $match[1];
        }
        return false;
    }

    /**
     * 随机生成32位token
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年10月03日 16:10
     */
    private static function generateToken(): string
    {
        return md5(uniqid(microtime() . mt_rand(), true));
    }
}