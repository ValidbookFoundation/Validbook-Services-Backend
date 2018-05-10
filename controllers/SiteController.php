<?php

namespace app\controllers;

use app\modules\v1\helpers\FileContentHelper;
use Jose\Factory\JWKFactory;
use Jose\Object\JWKSet;
use yii\web\Controller;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return parent::behaviors();
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ]
        ];
    }

    /**
     * Displays homepage - list of API methods.
     *
     * @return string
     */
    public function actionIndex()
    {
//        $http = new Client(['base_uri' => 'http://validbook-api.local']);
//
//        $response = $http->post('/v1/users/authorize-client', [
//            'form_params' => [
//                'client_id' => \Yii::$app->request->get('client_id'),
//                'response_type' => \Yii::$app->request->get('response_type'),
//                'redirect_uri' => \Yii::$app->request->get('redirect_uri'),
//                'scope' => \Yii::$app->request->get("scope"),
//                'state' => \Yii::$app->request->get("state"),
//                'nonce' => \Yii::$app->request->get("nonce")
//            ]
//        ]);
//
//        $res = json_decode($response->getBody()->getContents(), true);
//
//        if (isset($res['data']['redirect'])) {
//            return $this->redirect($res['data']['redirect']);
//        }

        return $this->render('index');
    }

    public function actionJsonKeys()
    {
        $fileContent = file_get_contents(getenv("JWSK_PUB"));
        $jwk = JWKFactory::createFromKeyFile(getenv("JWSK_PUB"), '',
            [
                'use' => 'sig',
                'alg' => 'RS256',
                'kty' => 'RSA',
                'kid' => hash('sha256', $fileContent)
            ]);

        $jwkset = new JWKSet();

        $jwkset->addKey($jwk);

        $response = \Yii::$app->response;
        $response->format = \yii\web\Response::FORMAT_JSON;

        return $jwkset;
    }
}