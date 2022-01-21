<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemAdmin extends Migrator
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
        $this->table('system_admin', ['comment' => '系统管理员表'])
            ->addColumn('username', 'string', ['comment' => '用户名', 'default' => ''])
            ->addColumn('password', 'string', ['comment' => '密码', 'default' => ''])
            ->addColumn('nickname', 'string', ['comment' => '昵称', 'default' => ''])
            ->addColumn('avatar', 'string', ['comment' => '头像', 'default' => ''])
            ->addColumn('is_super', 'integer', ['comment' => '超级管理员', 'default' => 0, 'limit' => 1])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
