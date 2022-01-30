<?php

namespace app\adminapi\middleware;

use app\model\system\SystemOperationLogModel;
use think\facade\Event;
use think\Request;

class OperationLogMiddleware
{
    public function handle(Request $request, $next)
    {
        Event::listen('think\event\LogWrite', function ($event) {
            try {
                if ($event->log['op']) {
                    foreach ($event->log['op'] as $op) {
                        SystemOperationLogModel::record($op);
                    }
                }
            } catch (\Throwable $e) {
            }
        });
        return $next($request);
    }
}