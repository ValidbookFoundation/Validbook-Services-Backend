<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\components;


use app\modules\v1\filters\HttpBearerClientAuth;

/**
 * Class ClientRestController
 * @package app\modules\v1\components
 */
class ClientRestController extends RestController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'authenticator' => [
                'class' => HttpBearerClientAuth::className(),
                'except' => ['options']
            ]
        ]);
    }

}