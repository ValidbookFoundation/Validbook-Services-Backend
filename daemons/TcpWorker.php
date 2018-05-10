<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\daemons;


class TcpWorker
{
    private static $_connection = 'tcp://127.0.0.1:1234';

    public static function write(array $data)
    {
        $instance = stream_socket_client(self::$_connection);
        if($instance){
            fwrite($instance, json_encode($data) . "\n");
        }
    }

}