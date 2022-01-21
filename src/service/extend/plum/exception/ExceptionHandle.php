<?php

namespace plum\exception;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;

class ExceptionHandle extends Handle
{
    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        Exception::class
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        if (!$this->isIgnoreReport($exception)) {
            $data = [
                'message' => $exception->getMessage(),
                'url'     => request()->url(true),
                'file'    => $exception->getFile() . ' [' . $exception->getLine() . ']',
                'method'  => request()->method(),
                'ip'      => request()->ip(),
                'trace'   => $exception->getTraceAsString(),
                'header' => var_export(request()->header(), true),
                'param'  => var_export(request()->param(), true)
            ];
            try {
                $this->app->log->record($data, 'error');
            } catch (Exception $e) {
            }
        }
    }


    /**
     * 渲染异常
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2021年09月30日 20:32
     */
    public function render($request, Throwable $e): Response
    {
        $debug = app()->isDebug();
        $data = [
            'code'    => $e->getCode() ?: 400,
            'message' => $e->getMessage()
        ];

        if ($e instanceof DataNotFoundException || $e instanceof ModelNotFoundException) {
            $data['code'] = 400;
            $data['message'] = '数据获取失败';
        } else if (!$this->isIgnoreReport($e)) {
            //内部异常
            $data['code'] = 500;
            //生产模式,不返回明确原因
            if (!$debug) {
                $data['message'] = 'SERVER FAIL';
            } else {
                $data['message'] = $data['message'] . " [{$e->getFile()}] {$e->getLine()}";
            }
        }
        return json($data);
    }
}
