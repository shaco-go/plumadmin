<?php

namespace app\model\system;

use plum\basic\BaseModel;

class SystemOperationLogModel extends BaseModel
{
    protected $table = 'system_operation_log';

    /**
     * 关联表 - admin
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/28
     */
    public function admin()
    {
        return $this->belongsTo(SystemAdminModel::class, 'operator_id', 'id');
    }

    /**
     * 搜索器 - route
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/30
     */
    public function searchRouteAttr($query,$value)
    {
        $query->whereLike('route',"%$value%");
    }

    /**
     * 搜索器 -  message
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/30
     */
    public function searchMessageAttr($query,$value)
    {
        $query->whereLike('message',"%$value%");
    }

    /**
     * 搜索器 - ip
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/30
     */
    public function searchIpAttr($query,$value)
    {
        $query->whereLike('ip',"%$value%");
    }

    /**
     * 搜索器 - operator
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/30
     */
    public function searchOperatorAttr($query,$value)
    {
        $ids = SystemAdminModel::whereLike('nickname',"%$value%")
            ->column('id');
        if(!empty($ids)){
            $query->whereIn('operator_id',$ids);
        }
    }

    /**
     * 记录操作日志
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/28
     */
    public static function record($message)
    {
        //获取路由
        $route = '';
        if (request()->rule()) {
            $route = request()->rule()->getRoute();
        }
        self::create([
            'message'      => $message,
            'route'        => $route,
            'ip'           => request()->ip(),
            'params'       => var_export(request()->param(), true),
            'header'       => var_export(request()->header(), true),
            'operator_id' => request()->middleware('userinfo')['id'] ?? 0
        ]);
    }
}