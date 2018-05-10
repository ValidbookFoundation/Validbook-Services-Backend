<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/carlositos
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\identity\Identity;
use app\modules\v1\models\identity\IdentityKeysHistory;
use app\modules\v1\models\identity\IdentityPurposedKeys;
use app\modules\v1\models\identity\IdentityStatement;
use app\modules\v1\models\identity\LinkIdentityStatement;
use yii\filters\auth\HttpBearerAuth;
use Yii;

class IdentityController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'authenticator' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['options', 'statements']
            ]
        ]);
    }

    public function actionView()
    {
        $identity = Yii::$app->request->post('identity');
        $identityModel = Identity::find()->where(['identity' => $identity])->one();

        return $this->success($identityModel);
    }

    public function actionSaveDidDocument()
    {
        $statement = Yii::$app->request->post('statement');
        $identityObject = new Identity();

        if(!$identityObject->isJSON())
            return $this->failure('statement is not a valid json file');

        $identityObject->json = $statement;

        $identityObject->setProperties();

        if(!$identityObject->isVerified())
            return $this->failure('statement is not verified');
    }

    public function actionLinkStatement()
    {
        $statement = Yii::$app->request->post('statement');
        $statementId = Yii::$app->request->post('statement_id');
        $identity = Yii::$app->user->identity->identity;

        $model = new LinkIdentityStatement();
        $model->json = $statement;
        $model->identity = $identity->identity;
        $model->identity_id = $identity->id;
        $model->identity_statement_id = $statementId;
        $model->identity_statement_uuid = $model->getUuidFromClaim();
        $model->is_ignored = 0;
        $model->is_revoked = 0;

        $model->save();
    }

    public function actionWaitingLinkedFeedback()
    {
        $identity = Yii::$app->user->identity->identity;

        $identityStatement = IdentityStatement::find()->where([
            'identity_id' => $identity->id,
            'type' => IdentityStatement::TYPE_UNIQUE_HUMAN_IDENTITY
        ])->one();

        if($identityStatement !== null)
            $this->failure("Identite does not have UNIQUE HUMAN IDENTITY STATEMENT");

        $users = LinkIdentityStatement::find()->where([
            'identity_statement_id' => $identityStatement->id,
            'is_ignored' => 0
        ])->all();

        return $this->success($users);
    }

    public function actionGraph()
    {
        //@TODO implement graph (dependency on FE)
    }

    public function actionGenerateKeys()
    {
        $publicAddress = Yii::$app->request->post('address');
        $recoveryAddress = Yii::$app->request->post('recovery_address');

        $identity = Yii::$app->user->identity->getSlug();
        $identityModel = Identity::find()->where(['identity' => $identity])->one();

        if($identityModel === null)
            return $this->failure("Identity does not exist");

        if(empty($identityModel->public_address) && empty($identityModel->recovery_address)) {
            $identityModel->updateAttributes([
                'public_address' => $publicAddress,
                'recovery_address' => $recoveryAddress
            ]);

            $userKeys = new IdentityKeysHistory();
            $userKeys->identity_id = $identityModel->id;
            $userKeys->recovery_address = $recoveryAddress;
            $userKeys->public_address = $publicAddress;
            $userKeys->is_revoked = 0;
            $userKeys->save();

            //@TODO generate new DID document
        }

        return $this->success(true);
    }

    public function actionSaveStatement()
    {
        $statement = Yii::$app->request->post('statement');
        $identity = Yii::$app->user->identity->identity;

        $model = new IdentityStatement();
        $model->json = $statement;

        if(!$model->isJSON())
            return $this->failure('statement is not a valid json file');

        $model->identity = $identity->identity;
        $model->setProperties();

        if(!$model->isVerified())
            return $this->failure('statement is not verified');

        if(!IdentityStatement::find()->where(['uuid' => $model->uuid])->exists()) {
            if($model->save())
                return $this->success(true);
        }

        return $this->failure();
    }

    public function actionStatements()
    {
        $identity = Yii::$app->request->get('identity');
        $identityModel = Identity::find()->where(['identity' => $identity])->one();

        if($identityModel === null)
            return $this->failure('Identity not found');

        return $identityModel->statements;
    }

    public function actionAddKeyToIdentity()
    {
        $address = Yii::$app->request->post('address');
        $purpose = Yii::$app->request->post('purpose');
        $identity = Yii::$app->user->identity->identity;

        $model = new IdentityPurposedKeys();
        $model->public_address = $address;
        $model->purpose = $purpose;
        $model->identity = $identity->identity;
        if($model->save())
            return $this->success(true);

        return $this->failure();
    }

    public function actionRevokeKey()
    {
        $address = Yii::$app->request->post('address');
        $identity = Yii::$app->user->identity->identity;

        $model = Identity::find()->where(['public_address' => $address])->one();
        if($model !== null) {
            if($model->identity === $identity) {
                //@TODO generate new addresses on FE and send them to BE
                return $this->success(true);
            }
        }

        $model = IdentityPurposedKeys::find()->where(['public_address' => $address])->one();
        if($model !== null) {
            if($model->identity === $identity) {
                $model->updateAttributes(['is_revoked' => 1]);
                return $this->success(true);
            }
        }

        return $this->failure("$identity->identity has no access to $address");
    }

    //all keys includes revocated
    public function actionListPurposedKeys()
    {
        $identity = Yii::$app->request->get('identity');

        $identityModel = Identity::find()->where(['identity' => $identity])->one();

        if($identityModel === null)
            return $this->failure('Identity not found');

        return $identityModel->purposedKeys;
    }
}