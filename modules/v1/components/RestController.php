<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\components;

use app\exceptions\ValidateErrorHttpException;
use Yii;
use yii\filters\Cors;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UnauthorizedHttpException;

class RestController extends Controller
{
    // grabbed from yii\rest\OptionsAction with a little work around
    private $_verbs = ['POST', 'PUT', 'DELETE', 'PATCH', 'OPTIONS'];

    public $serializer = [
        'class' => 'yii\rest\Serializer',
        'collectionEnvelope' => 'items',
    ];

    protected $isOwner = false;

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => Cors::className(),
                'cors' => [
                    // restrict access to
                    'Origin' => Yii::$app->params['allowedDomains'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Headers' => ['*'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                ],

            ],
            'contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'text/html' => Response::FORMAT_JSON,
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ]
        ];
    }

    protected function success($data = [], $statusCode = 200, $status = 'success')
    {
        Yii::$app->response->setStatusCode($statusCode);

        $result = [
            'status' => $status,
            'data' => $data
        ];


        return $result;
    }

    protected function shortSuccess($data = [], $statusCode = 200, $status = 'success')
    {
        Yii::$app->response->setStatusCode($statusCode);

        return $data;
    }

    /**
     * @param string $message
     * @param int $statusCode
     * @return array
     * @throws BadRequestHttpException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @throws UnauthorizedHttpException
     * @throws ValidateErrorHttpException
     */
    protected function failure($message = '', $statusCode = 404)
    {
        if (is_array($message)) {
            Yii::$app->response->setStatusCode($statusCode);
            $result = [
                'status' => 'error',
                'errors' => [
                    [
                        'message' => $message,
                    ]
                ]
            ];
            return $result;
        }

        switch ($statusCode) {
            case $statusCode === 400:
                throw new BadRequestHttpException($message);
                break;
            case $statusCode === 401:
                throw new UnauthorizedHttpException($message);
                break;
            case $statusCode === 422:
                throw new ValidateErrorHttpException($message);
                break;
            case $statusCode === 500:
                throw new ServerErrorHttpException($message);
                break;
            default:
                throw new NotFoundHttpException($message);
        }
    }

    public function actionOptions()
    {
        if (Yii::$app->getRequest()->getMethod() !== 'OPTIONS') {
            Yii::$app->getResponse()->setStatusCode(405);
        }
        $options = $this->_verbs;
        Yii::$app->getResponse()->getHeaders()->set('Allow', implode(', ', $options));
    }

}