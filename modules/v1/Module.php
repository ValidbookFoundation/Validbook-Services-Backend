<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1;

use app\modules\v1\components\ApiErrorHandler;

class Module extends \yii\base\Module
{
    public function init()
    {
        parent::init();

        $handler = new ApiErrorHandler;
        \Yii::$app->set('errorHandler', $handler);
        $handler->register();
    }

    public function getVersion()
    {
        return '/' . $this->id;
    }
}