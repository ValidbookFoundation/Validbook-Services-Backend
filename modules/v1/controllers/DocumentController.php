<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\box\Box;
use app\modules\v1\models\card\Card;
use app\modules\v1\models\doc\Document;
use app\modules\v1\models\doc\DocumentEncrypted;
use app\modules\v1\models\doc\DocumentFile;
use app\modules\v1\models\doc\DocumentPermissionSettings;
use app\modules\v1\models\forms\UploadDocFileForm;
use app\modules\v1\models\forms\UploadDocForm;
use app\modules\v1\models\User;
use Yii;
use yii\db\Transaction;
use yii\web\UploadedFile;

/**
 * Class DocumentController
 * @package app\modules\v1\controllers
 */
class DocumentController extends Controller
{
    public function actionView($id)
    {
        $doc = Document::findOne($id);

        if ($doc !== null && $doc->isContentVisible()) {
            //only array can be formatted
            return $this->success($doc->formatCard());
        } else
            return $this->failure("Access denied", 403);
    }


    public function actionCreate()
    {
        $boxSlug = Yii::$app->request->post('box_slug');
        $userId = Yii::$app->getUser()->getId();
        $title = Yii::$app->request->post('title');
        $body = Yii::$app->request->post('content');
        $encrypted = Yii::$app->request->post('is_encrypted');
        $hash = Yii::$app->request->post('hash');

        if ($boxSlug == null) {
            return $this->failure("box_slug can not be empty", 422);
        }

        if ($encrypted === null) {
            return $this->failure("is_encrypted required", 422);
        }

        if ($encrypted) {
            if (empty($hash)) {
                return $this->failure("If content encrypted hash can not be empty", 422);
            }

            /** @var User $user */
            $user = Yii::$app->getUser()->identity;

            $accCard = Card::findOne(['public_address' => $user->userKey->public_address, 'is_revoked' => 0]);

            if (empty($accCard)) {
                return $this->failure("You can't encrypt message without account card", 422);
            }

        }

        if (!$this->hasOwnerAccessRights(User::className(), 'id', $userId)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if ($boxSlug == 'root' || $boxSlug == 'signed') {
            return $this->failure("You can not add document to the box", 401);
        }

        /** @var Box $boxModel */
        $boxModel = Box::find()->where([
            'slug' => $boxSlug,
            'user_id' => $userId,
            'is_moved_to_bin' => 0
        ])->one();

        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {
            $model = new Document();
            $model->user_id = $userId;
            $model->box_id = $boxModel->id;
            $model->title = $title;
            $model->content = $body;

            if ($encrypted) {
                $model->hash = $hash;
                $model->is_encrypted = 1;
                $model->icon = Yii::$app->params["closedDocumentIcon"];
            } else {
                $model->is_encrypted = 0;
                $model->icon = $model->setPreviewImage();
            }

            if ($model->validate() and $model->save()) {
                if (!empty($body)) {
                    if (!$model->saveDocument($body, false, $encrypted)) {
                        return $this->failure("file does not save", 422);
                    };
                    $model->update();
                }

                if (!DocumentPermissionSettings::setValues($model->id)) {
                    $transaction->rollBack();
                }
                $transaction->commit();
                return $this->success($model->formatCard());

            } else {
                $transaction->rollBack();

                return $this->failure($model->errors, 422);
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->failure($e->getMessage(), $e->getCode());
        }
    }


    public function actionFileAttach($id)
    {
        $doc = Document::findOne($id);

        if ($doc->is_signed) {
            return $this->failure('You can not attach file to signed document', 422);
        }

        if (!$this->hasOwnerAccessRights(User::className(), 'id', $doc->user_id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $modelFile = new UploadDocFileForm();

        $modelFile->file = UploadedFile::getInstanceByName('file');

        if (!empty($modelFile->file)) {
            $result = $modelFile->uploadFile($doc->id);
            if (!empty($result)) {
                return $this->success(['file_url' => $result]);
            } else {
                return $this->failure('File did not save', 422);
            }

        } else {
            return $this->failure('File can not be empty', 422);
        }
    }

    public function actionFileRemove($id)
    {
        $fileUrl = Yii::$app->request->post('file_url');
        $doc = Document::findOne($id);


        if ($doc->is_signed) {
            return $this->failure('You can not delete file from signed document', 422);
        }

        if ($fileUrl == null) {
            return $this->failure('File Url can not be empty', 422);
        }

        if (!$this->hasOwnerAccessRights(User::className(), 'id', $doc->user_id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        /** @var DocumentFile $model */
        $model = DocumentFile::findOne(['url' => $fileUrl, 'doc_id' => $doc->id]);

        if ($model->deleteFromBucket()) {
            $model->delete();
            return $this->success();
        } else {
            return $this->failure('Unexpected error', 500);
        }
    }


    public function actionUpdate($id)
    {
        $title = Yii::$app->request->post('title');
        $file = Yii::$app->request->post('content');
        $hash = Yii::$app->request->post('hash');


        if (!$this->hasOwnerAccessRights(Document::className(), 'user_id', $id))
            return $this->failure("You are not allowed to perform this action", 401);

        /** @var Document $model */
        $model = Document::find()->where(['id' => $id, 'is_moved_to_bin' => 0])->one();


        if (empty($model)) {
            return $this->failure("Document does not exists");
        }

        if (!$model->canUpdate()) {
            return $this->failure("You can't update signed document", 401);
        }

        if ($model->is_encrypted) {
            if (empty($hash)) {
                return $this->failure("Hash can not be empty if document was encrypted", 401);
            }
        }

        if (!empty($file)) {
            $model->updateDocumentFile($file);
            if (!$model->is_encrypted) {
                $model->hash = $model->hashMessage($file);
                $model->icon = $model->setPreviewImage();
            } else {
                $model->hash = $hash;
            }
        }

        if (!empty($title)) {
            $model->title = $title;
        }

        if ($model->validate()) {
            $model->update();

            return $this->success($model->formatCard());
        }

        return $this->failure($model->errors);

    }


    public function actionDelete($id)
    {
        /** @var Document $model */
        $model = Document::find()->where(['id' => $id, 'is_moved_to_bin' => 0])->one();

        if (!empty($model)) {
            if ($model->user_id != Yii::$app->user->id) {
                return $this->failure("You are not allowed to perform this action", 401);
            }
            if ($model->isMovedToBin()) {
                return $this->success();
            }

        } else {
            return $this->failure("Document does not exists");
        }

    }

    public function actionRemove($id)
    {
        /** @var Document $model */
        $model = Document::find()->where(['id' => $id, 'is_moved_to_bin' => 1])->one();

        if (!empty($model)) {
            if ($model->user_id != Yii::$app->user->id) {
                return $this->failure("You are not allowed to perform this action", 401);
            }

            if ($model->is_encrypted) {
                $modelsEncrypted = DocumentEncrypted::findAll(['document_id' => $model->id]);
                foreach ($modelsEncrypted as $value) {
                    $value->delete();
                }
            }

            $model->delete();

            return $this->success();

        } else {
            return $this->failure("Document does not exists");
        }

    }

    public function actionRecover($id)
    {
        /** @var Document $model */
        $model = Document::find()->where(['id' => $id])->one();

        if (empty($model)) {
            return $this->failure("Document does not exists");
        }

        if (!$this->hasOwnerAccessRights(Document::className(), 'user_id', $model->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model->updateAttributes(['is_moved_to_bin' => 0]);

        return $this->success();
    }

    public function actionOpenForSig($id)
    {
        /** @var Document $doc */
        $doc = Document::findOne($id);

        if ($doc->is_open_for_sig) {
            return $this->failure("Document was opened to sign", 422);
        }

        if ($doc->is_signed) {
            return $this->failure("Document was signed", 422);
        }

        if (!$this->hasOwnerAccessRights(Document::className(), 'user_id', $doc->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (empty($doc)) {
            return $this->failure("Document does not exist");
        }


        $messageWithNonce = $doc->messageForOpenSig();

        $doc->hash = $doc->hashMessage($messageWithNonce);
        $doc->icon = $doc->setPreviewImage();

        $doc->is_open_for_sig = true;

        if ($doc->update()) {
            return $this->success(['message' => $messageWithNonce]);
        }

        return $this->failure($doc->errors);
    }

    public function actionMessageForSig($id)
    {
        /** @var Document $doc */
        $doc = Document::findOne($id);

        if (empty($doc)) {
            return $this->failure("Document does not exist");
        }

        if(!$doc->is_open_for_sig){
            return $this->failure("Document does not open for signature");
        }

        if ($doc->is_signed) {
            return $this->failure("Document was signed", 422);
        }

        $message = $doc->messageForSig();

        return $this->success($message);
    }

    public function actionSignature($id)
    {
        $signature = Yii::$app->request->post('signature');

        /** @var Document $model */
        $model = Document::find()->where(['id' => $id, 'is_moved_to_bin' => 0])->one();

        if (empty($model)) {
            return $this->failure("Document does not exists");
        }

        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        /** @var Card $ac */
        $userCardAddress = $user->userKey->public_address;

        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        if ($model->is_encrypted) {
            $model->is_signed = true;
            $model->update();
            Document::copyToSigned($model->user_id, $model);
            $transaction->commit();

            return $this->success($model->formatCard());

        } else {
            if ($model->validateSign($userCardAddress, $signature)) {
                $model->is_signed = true;
                $model->update();
                Document::copyToSigned($model->user_id, $model);

                $transaction->commit();

                return $this->success($model->formatCard());
            }
            $transaction->rollBack();

            return $this->failure("Signature not valid", 422);
        }
    }


    public function actionMove($id)
    {
        $user = Yii::$app->getUser();

        $boxSlug = Yii::$app->request->post('box_slug');
        $doc = Document::findOne(['id' => $id]);
        $currentBox = Box::findOne($doc->box_id);


        if ($user->id !== $doc->user_id) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $box = Box::findOne(['user_id' => $user->id, 'slug' => $boxSlug, 'is_moved_to_bin' => 0]);

        if (empty($box)) {
            if ($boxSlug !== 'bin') {
                return $this->failure("Box does not exist");
            }
        }

        if ($boxSlug == $currentBox->slug) {
            return $this->failure("You can not move document to current box", 401);
        }
        if ($box->name == Box::DEFAULT_SIGNED_BOX) {
            return $this->failure("You can not move document to this box", 401);
        }

        $doc->box_id = $box->id;

        if ($currentBox == 'bin') {
            $doc->is_moved_to_bin = 0;
        }

        if ($doc->update()) {
            return $this->success($doc->formatCard());
        }
        return $this->failure("Unexpected error", 500);
    }


    public function actionCopy($id)
    {
        $boxSlug = Yii::$app->request->post('box_slug');

        $doc = Document::findOne(['id' => $id, 'is_moved_to_bin' => 0]);

        $user = Yii::$app->getUser()->identity;

        $box = Box::findOne(['user_id' => $user->getId(), 'slug' => $boxSlug, 'is_moved_to_bin' => 0]);

        if (empty($box)) {
            return $this->failure("Box does not exist");
        }

        $transaction = Yii::$app->db->beginTransaction(
            Transaction::SERIALIZABLE
        );

        try {
            $model = new Document();
            $model->box_id = $box->id;
            $model->type = $doc->type;
            $model->user_id = $user->getId();
            $model->url = $doc->url;
            $model->hash = $doc->hash;
            $model->is_signed = $doc->is_signed;
            $model->is_encrypted = $doc->is_encrypted;

            if (!$doc->is_signed) {
                $model->title = $doc->title . '_copy' . $doc->id;
            } else {
                $model->title = $doc->title;
            }

            if ($model->save()) {
                if ($model->is_encrypted) {
                    $modelsEncryptedDocs = DocumentEncrypted::findAll(['document_id' => $doc->id]);
                    foreach ($modelsEncryptedDocs as $encryptedDoc) {
                        $newModelEnc = new DocumentEncrypted();
                        $newModelEnc->url = $encryptedDoc->url;
                        $newModelEnc->receiver_public_address = $encryptedDoc->receiver_public_address;
                        $newModelEnc->document_id = $model->id;
                        $newModelEnc->save();
                    }
                }
                if (!DocumentPermissionSettings::setValues($model->id)) {
                    $transaction->rollBack();
                }
                $transaction->commit();
                return $this->success($model->formatCard());
            }
        } catch (\Exception $e) {
            $transaction->rollBack();

            return $this->failure($e->getMessage(), $e->getCode());
        }
    }

    public function actionDownload($id)
    {
        $doc = Document::findOne(['id' => $id, 'is_moved_to_bin' => 0]);

        if (!$this->hasOwnerAccessRights(Document::className(), 'user_id', $doc->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }


        return $this->success(['document_url' => $doc->fullDocument()]);
    }

    public function actionUpload()
    {
        $boxSlug = Yii::$app->request->post("box_slug");
        $userId = Yii::$app->getUser()->getId();

        /** @var Box $box */
        $box = Box::find()->where(["slug" => $boxSlug, "user_id" => $userId, 'is_moved_to_bin' => 0])->one();

        if (empty($box)) {
            return $this->failure("Box does not exist");
        }

        if (!$this->hasOwnerAccessRights(Box::className(), 'user_id', $box->id)) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $modelFile = new UploadDocForm();

        $modelFile->file = UploadedFile::getInstanceByName('file');

        if (empty($modelFile->file)) {
            return $this->failure("File can not be empty", 422);
        }

        $model = new Document();
        $model->is_encrypted = 0;
        $model->box_id = $box->id;
        $model->user_id = $userId;
        $model->title = $modelFile->file->baseName;

        if (!$model->save()) {
            return $this->failure($model->errors, 422);
        }

        if (!DocumentPermissionSettings::setValues($model->id)) {
            return $this->failure("Document did not upload", 422);
        }

        if (!$model->upload($modelFile)) {
            return $this->failure("Document did not upload", 422);
        }

        return $this->success($model->formatCardForUpload(), 201);
    }

}
