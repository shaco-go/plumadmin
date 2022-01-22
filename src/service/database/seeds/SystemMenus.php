<?php

use think\migration\Seeder;

class SystemMenus extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $rule = $this->table('system_menus');
        if ($rule->exists()) {
            $this->execute('TRUNCATE TABLE system_menus');
            $data = include_once __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'system_menus.json';
            $rule->insert(json_decode($data, true))
                ->save();
        }
    }
}