<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/carlositos
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\helpers\FileContentHelper;
use app\modules\v1\models\identity\IdentityStatement;
use app\modules\v1\models\statement\StatementTemplate;
use Yii;
use yii\filters\auth\HttpBearerAuth;

/**
 * Class StatementController
 * @package app\modules\v1\controllers
 */
class StatementController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'authenticator' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['options', 'verify']
            ]
        ]);
    }

    public function actionVerify()
    {
        $message = Yii::$app->request->post('message');

        $model = new IdentityStatement();
        $model->json = $message;
        $model->setProperties();

        if(!$model->hasProof())
            return $this->failure("This message has not proof");

        return $this->success($model->isVerified());
    }

    public function actionTemplates()
    {
        $templates = StatementTemplate::find()->select(['id', 'title', 'json', 'default','will_be_json_changed'])
            ->with('templates')
            ->orderBy('sort')
            ->asArray()
            ->all();

        foreach ($templates as $keyJ => $row) {
            if($row['will_be_json_changed'] == 1)
                $templates[$keyJ]['json'] = StatementTemplate::regenerateJson($row['json'], $row['id']);
        }

        return $this->success($templates);
    }

    public function actionHtmlTemplate()
    {
        $link = Yii::$app->request->post('link');
        $content = FileContentHelper::get($link);

        if(!$content)
            return $this->failure("Cannot get content from $link");

        return $this->success($content);
    }
}