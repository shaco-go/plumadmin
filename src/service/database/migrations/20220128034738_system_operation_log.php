<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemOperationLog extends Migrator
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
        $this->table('system_operation_log', ['comment' => '操作日志'])
            ->addColumn('message', 'string', ['comment' => '操作信息', 'default' => ''])
            ->addColumn('route', 'string', ['comment' => '操作路由', 'default' => ''])
            ->addColumn('ip', 'string', ['comment' => '路由方法', 'default' => ''])
            ->addColumn('params', 'text', ['comment' => '参数'])
            ->addColumn('header', 'text', ['comment' => 'header参数'])
            ->addColumn('operator_id','integer',['comment'=>'操作账号','default'=>0])
            ->addTimestamps()
            ->save();
    }
}
