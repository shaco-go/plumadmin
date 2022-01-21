<?php

namespace app\model\system;

use plum\basic\BaseModel;
use think\model\concern\SoftDelete;

class SystemAttachmentCategoryModel extends BaseModel
{
    use SoftDelete;

    protected $name = 'system_attachment_category';
}