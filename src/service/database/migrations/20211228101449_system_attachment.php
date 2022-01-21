<?php

use think\migration\Migrator;
use think\migration\db\Column;

class SystemAttachment extends Migrator
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
        $this->table('system_attachment', ['comment' => "附件表"])
            ->addColumn('name', 'string', ['comment' => '文件名', 'default' => ''])
            ->addColumn('path', 'string', ['comment' => '文件地址', 'default' => ''])
            ->addColumn('driver', 'string', ['comment' => '上传类型', 'default' => ''])
            ->addColumn('mime', 'string', ['comment' => 'MIME类型', 'default' => ''])
            ->addColumn('size', 'integer', ['comment' => '文件大小(B)', 'default' => 0])
            ->addColumn('module', 'string', ['comment' => '应用名称(多应用)', 'default' => ''])
            ->addColumn('category_id', 'integer', ['comment' => '分类ID', 'default' => 0])
            ->addColumn('operator_id', 'integer', ['comment' => '操作者ID', 'default' => 0])
            ->addTimestamps()
            ->addSoftDelete()
            ->save();
    }
}
