<?php

namespace app\model\system;

use plum\basic\BaseModel;
use think\model\concern\SoftDelete;

class SystemMenusModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'system_menus';
    protected $type = ['method' => 'json', 'menu_hidden' => 'boolean', 'keep_alive' => 'boolean'];

    const TYPE_DIRECTORY = 1;
    const TYPE_MENU = 2;
    const TYPE_PERMISSION = 3;
}