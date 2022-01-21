<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemAdminRole extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->table('system_admin_role', ['comment' => '管理员规则表'])
            ->addColumn('admin_id', 'integer', ['comment' => '管理员ID', 'default' => 0])
            ->addColumn('role_id', 'integer', ['comment' => '角色ID', 'default' => 0])
            ->save();
    }
}
