<?php

namespace app\adminapi;

use app\model\system\SystemAdminModel;
use plum\traits\ResponseTrait;
use think\App;
use think\Request;

class Controller
{
    use ResponseTrait;

    /**
     * Request实例
     * @var Request
     */
    protected $request;

    /**
     * 应用实例
     * @var App
     */
    protected $app;

    /**
     * @var SystemAdminModel
     */
    protected $userinfo;


    /**
     * 构造方法
     * @access public
     * @param App $app 应用对象
     */
    final public function __construct(App $app)
    {
        $this->app = $app;
        $this->request = $this->app->request;
        $this->request->middleware('userinfo');
        $this->userinfo = $this->request->middleware('userinfo') ?? null;
        // 控制器初始化
        $this->initialize();
    }

    // 初始化
    protected function initialize()
    {
    }
}