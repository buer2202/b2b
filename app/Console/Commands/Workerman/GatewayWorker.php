<?php

namespace App\Console\Commands\Workerman;

use Illuminate\Console\Command;
use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use Workerman\Worker;

class GatewayWorker extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gatewayworker {action} {--d}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a GatewayWorker server.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        global $argv;
        $action = $this->argument('action');

        $argv[0] = 'artisan gatewayworker';
        $argv[1] = $action;
        $argv[2] = $this->option('d') ? '-d' : '';

        $this->start();
    }

    private function start()
    {
        $this->startRegister();
        $this->startGateWay();
        $this->startBusinessWorker();
        Worker::runAll();
    }

    private function startBusinessWorker()
    {
        $worker = new BusinessWorker();
        $worker->name         = 'BusinessWorker';
        $worker->count        = 1;
        $worker->eventHandler = \App\Workerman\Events::class;
        // $worker->registerAddress = '127.0.0.1:1236'; // 127.0.0.1:1236 是默认值
    }

    private function startGateWay()
    {
        $gateway = new Gateway("websocket://0.0.0.0:20000");
        $gateway->name  = 'BuerGateway';
        $gateway->count = 1;
        // $gateway->lanIp           = '127.0.0.1'; // 127.0.0.1 是默认值
        // $gateway->startPort       = 2000; // 2000 是默认值
        // $gateway->registerAddress = '127.0.0.1:1236'; // 127.0.0.1:1236 是默认值

        // 心跳设置
        $gateway->pingInterval         = 30;
        $gateway->pingNotResponseLimit = 0;
        $gateway->pingData             = '{"type":"@heart@"}';
    }

    private function startRegister()
    {
        new Register('text://0.0.0.0:1236');  // 1236 是默认端口，不改的话，调用的时候可以少写一行代码：Gateway::$registerAddress = '127.0.0.1:2202';
    }
}
