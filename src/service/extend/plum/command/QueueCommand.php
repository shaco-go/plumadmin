<?php

namespace plum\command;

use plum\lib\Queue;
use plum\lib\queue\CreateQueue;
use plum\utils\Helper;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class QueueCommand extends Command
{
    public function configure()
    {
        $this->setName('worker:queue')
            ->addArgument('action', Argument::OPTIONAL, 'start|stop|restart|reload|status', 'start')
            ->addOption('daemon', 'd', Option::VALUE_NONE, 'Run the workerman server in daemon mode.')
            ->setDescription('timer');
    }

    public function execute(Input $input, Output $output)
    {
        //队列对象
        $host = env('redis.host', '127.0.0.1');
        $port = env('redis.port', 6379);
        $auth = env('redis.password', '');
        $db = env('redis.select', 0);
        //只有redis连通的情况下才开启队列
        if ($this->redisPing($host, $port, $auth)) {
            $queueClient = new Queue("redis://{$host}:{$port}", [
                'auth' => $auth,
                'db'   => $db
            ]);
            $worker = new Worker();
            $action = $input->getArgument('action');
            $daemon = $input->getOptions('daemon');
            //如果在linux系统下,改造一下argv参数,好兼容workerman
            if (Helper::isLinux()) {
                global $argv;
                $argv = [];
                array_unshift($argv, 'think', $action);
                if ($daemon) {
                    $argv[] = '-d';
                }
            }
            $worker->onWorkerStart = function (Worker $task) use ($queueClient) {
                $queue = config('queue');
                //队列的订阅
                foreach ($queue as $item) {
                    $obj = app()->make($item);
                    $queueClient->subscribe($obj->name(), function ($data) use ($obj) {
                        call_user_func_array([$obj, 'handle'], [$data]);
                    });
                }
            };
            Worker::runAll();
        } else {
            $output->writeln('队列开启失败,redis未开启或配置错误');
        }
    }

    /**
     * redis是否连通
     * @author Plum
     * @email liujunyi_coder@163.com
     * @time 2022/1/18
     */
    public function redisPing($host, $port = 6379, $auth = null)
    {
        if (!extension_loaded('redis')) {
            return false;
        }
        try {
            $redis = new \Redis();
            $redis->connect($host, $port);
            if ($auth) {
                $redis->auth($auth);
            }
            $redis->ping('success');
        } catch (\Throwable $e) {
            return false;
        }
        return true;
    }
}