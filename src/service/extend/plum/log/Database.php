<?php

namespace plum\log;

use app\model\system\SystemDebugLogModel;
use think\contract\LogHandlerInterface;
use think\facade\Log;

class Database implements LogHandlerInterface
{
    public function save(array $log): bool
    {
        // 只记录error
        if(empty($log['error'])){
            return true;
        }
        try{
            foreach($log['error'] as $error){
                SystemDebugLogModel::create($error);
            }
        }catch(\Throwable $e){
        }
        return true;
    }
}