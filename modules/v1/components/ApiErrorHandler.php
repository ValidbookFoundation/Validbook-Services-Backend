<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\components;

use Yii;
use yii\web\Response;

class ApiErrorHandler extends \yii\web\ErrorHandler
{

    /**
     * @inheridoc
     */

    protected function renderException($exception)
    {
        if (Yii::$app->has('response')) {
            $response = Yii::$app->getResponse();
        } else {
            $response = new Response();
        }

        $response->data = $this->convertExceptionToArray($exception);
        $response->setStatusCode($exception->statusCode);

        $response->send();
    }

    /**
     * @inheritdoc
     */

    protected function convertExceptionToArray($exception)
    {
        return [
            'status'=>'error',
            'errors'=>[
                [
                    'code'=>$exception->statusCode,
                    'message'=>$exception->getMessage()
                ]
            ]
        ];
    }
}