<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use app\daemons\AppWorker;
use Workerman\Worker;
use yii\console\Controller;

class ServerSocketController extends Controller
{
    public function actionIndex($status)
    {
        $users = [];

        $app_work = new AppWorker("websocket://0.0.0.0:8000");
        switch ($status) {
            case 'start':

        }

        $app_work->onWorkerStart = function () use (&$users) {
            $inner_tcp_worker = new Worker("tcp://127.0.0.1:1234");

            $inner_tcp_worker->onMessage = function ($connection, $data) use (&$users) {
                $data = json_decode($data);
                if(isset($users[$data->user])){
                    $webconnections = $users[$data->user];
                    foreach ($webconnections as $con) {
                        $con->send($data->message);
                    }
                }
            };
            $inner_tcp_worker->listen();
        };
        $app_work->onConnect = function ($connection) use (&$users) {
            $connection->onWebSocketConnect = function ($connection) use (&$users) {
                if(isset($_GET['user'])){
                    $users[$_GET['user']][$connection->id] = $connection;
                    $connection->user = $_GET['user'];
                }
            };
        };
        $app_work->onClose = function ($connection) use (&$users) {
            if (!isset($connection->user)) return;
            unset($users[$connection->user][$connection->id]);
        };

        $app_work->args($status, true, 'server');
        $app_work->runAll();
    }
}