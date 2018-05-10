<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\daemons;


class AppWorker extends YiiWorker
{
    public static $args=[];
    /**
     * AppWorker constructor.
     * @param string $socket_name
     * @param array $context_option
     */
    public function __construct($socket_name, array $context_option = [])
    {
        parent::__construct($socket_name, $context_option);

    }
}