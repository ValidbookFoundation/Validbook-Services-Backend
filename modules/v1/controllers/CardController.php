<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\jobs\AddFullCard;
use app\modules\v1\models\card\Card;
use app\modules\v1\models\card\CardClaim;
use app\modules\v1\models\card\CardSupport;
use app\modules\v1\models\card\DimClaimCard;
use app\modules\v1\models\card\DimSupportCard;
use app\modules\v1\models\hcard\HCardDigitalProperty;
use app\modules\v1\models\redis\DraftHumanClaim;
use app\modules\v1\models\redis\DraftHumanClaimSupport;
use app\modules\v1\models\redis\NodeValidator;
use app\modules\v1\models\Signature;
use app\modules\v1\models\User;
use Yii;

class CardController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = array_merge($behaviors['authenticator']['except'], ['view']);

        return $behaviors;
    }

    public function actionView($address)
    {
        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (!empty($card)) {
            return $this->success($card->getFormattedCard());
        } else {
            return $this->failure("Model does not exist");
        }
    }

    public function actionHumanClaimMessage($address)
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        $modelCard = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($modelCard)) {
            return $this->failure("Card does not exist");
        }

        if ($modelCard->public_address !== $user->userKey->public_address) {
            return $this->failure("You are not allowed this action", 401);
        }

        /** @var CardClaim $modelClaim */
        $modelClaim = CardClaim::find()
            ->where([
                'card_address' => $modelCard->public_address,
                'claim_id' => DimClaimCard::HUMAN_CLAIM_ID])
            ->one();

        if (!empty($modelClaim)) {
            return $this->failure("You are already have this claim", 422);
        }

        $modelClaim = DraftHumanClaim::findOne(['card_address' => $address]);
        if (!empty($modelClaim)) {
            $modelClaim->delete();
        }

        $modelClaim = new DraftHumanClaim();
        $modelClaim->claim_id = DimClaimCard::HUMAN_CLAIM_ID;
        $modelClaim->card_address = $address;

        $message = $modelClaim->getMessageForHumanClaimSig();
        $modelClaim->save();

        return $this->success(['message' => $message]);
    }

    public function actionHumanClaimSig($address)
    {
        $signature = Yii::$app->request->post('signature');

        if (empty($signature)) {
            return $this->failure("Signature not valid");
        }

        $draftClaim = DraftHumanClaim::findOne(['card_address' => $address]);

        if (empty($draftClaim)) {
            return $this->failure("DraftClaim does not exist", 422);
        }

        /** @var Card $modelCard */
        $modelCard = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($modelCard)) {
            return $this->failure("Card does not exist", 422);
        }

        /** @var User $user */
        $user = Yii::$app->user->identity;

        if ($user->userKey->public_address !== $address) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $cardClaim = new CardClaim();
        $cardClaim->card_address = $draftClaim->card_address;
        $cardClaim->claim_id = $draftClaim->claim_id;

        if (!$cardClaim->validateSig($user->id, $signature, $draftClaim->message)) {
            return $this->failure("Signature  is not valid", 422);
        }

        $draftClaim->delete();

        return $this->success($modelCard->getFormattedCard());

    }

    public function actionAddDigitalProperty($address)
    {
        $property = Yii::$app->request->post("property");
        $urlProperty = Yii::$app->request->post("url_to_property");

        if ($property == null) {
            return $this->failure("Property can not be empty", 422);
        }

        if ($urlProperty == null) {
            return $this->failure("Url to property can not be empty", 422);
        }

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($card)) {
            return $this->failure("Card does not exist");
        }

        $user = $card->getOwnerOfCard();

        if (empty($user)) {
            return $this->failure("User does not exist");
        }

        if ($user->id !== Yii::$app->getUser()->getId()) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        $model = new HCardDigitalProperty();

        if (!$model->addLink($property, $urlProperty, $card->public_address)) {
            return $this->failure($model->errors, 422);
        }

        return $this->success($model->getFormattedCard());
    }

    public function actionProofDigitalProperty($address)
    {
        $propertyId = Yii::$app->request->post('property_id');
        $propertyName = Yii::$app->request->post('property');
        $token = Yii::$app->request->post('facebook_token');

        /** @var HCardDigitalProperty $property */
        $property = HCardDigitalProperty::find()->where(['id' => $propertyId])->one();

        if (empty($property)) {
            return $this->failure("Property does not exist", 422);
        }

        if ($property->is_valid == 1) {
            return $this->failure("Property was proofed", 422);
        }

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        $hcProperty = HCardDigitalProperty::findOne([
            'card_address' => $card->public_address,
            'property' => HCardDigitalProperty::getPropertyType($propertyName),
            'is_valid' => 1
        ]);

        if (!empty($hcProperty)) {
            return $this->failure("Property was already proofed", 422);
        }

        if (empty($card)) {
            return $this->failure("Card does not exist");
        }

        $user = $card->getOwnerOfCard();

        if (empty($user)) {
            return $this->failure("User does not exist");
        }

        if ($user->id !== Yii::$app->getUser()->getId()) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if ($property->card_address !== $card->public_address) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if ($property->property == HCardDigitalProperty::TYPE_TWITTER) {
            if (!$property->validateTweetProof()) {
                return $this->failure("Proof not found");
            }
        } elseif ($property->property == HCardDigitalProperty::TYPE_FACEBOOK) {
            if (!$property->validateFacebookProof($token)) {
                return $this->failure("Proof not found");
            }
        }

        return $this->success($property->getFormattedCard());
    }

    public function actionRevokeDigitalProperty($address)
    {
        $propertyId = Yii::$app->request->post('property_id');

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        /** @var HCardDigitalProperty $property */
        $property = HCardDigitalProperty::find()->where(['id' => $propertyId])->one();

        if (empty($property)) {
            return $this->failure("Property does not exist");
        }

        $user = $card->getOwnerOfCard();

        if (empty($user)) {
            return $this->failure("User does not exist");
        }

        if ($user->id !== Yii::$app->getUser()->getId()) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if ($property->card_address !== $card->public_address) {
            return $this->failure("You are not allowed to perform this action", 401);
        }

        if (!$property->delete()) {
            return $this->failure($property->errors, 422);
        }

        // job for added full card document
        \Yii::$app->queue->push(new AddFullCard([
            'public_address' => $card->public_address
        ]));

        return $this->success(true);

    }


    public function actionSupportHumanClaimMessage($address)
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;


        $supporterAddress = $user->userKey->public_address;

        $modelCard = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($modelCard)) {
            return $this->failure("Card does not exist");
        }

        if ($modelCard->public_address == $supporterAddress) {
            return $this->failure("You are not allowed this action", 401);
        }

        /** @var CardClaim $modelClaim */
        $modelClaim = CardClaim::find()
            ->where([
                'card_address' => $modelCard->public_address,
                'claim_id' => DimClaimCard::HUMAN_CLAIM_ID])
            ->one();

        if (empty($modelClaim)) {
            return $this->failure("Claim does not exist", 422);
        }


        /** @var CardClaim $modelClaimSupporter */
        $modelClaimSupporter = CardClaim::find()
            ->where([
                'card_address' => $supporterAddress,
                'claim_id' => DimClaimCard::HUMAN_CLAIM_ID])
            ->one();

        if (empty($modelClaimSupporter)) {
            return $this->failure("Claim of supporter does not exist", 422);
        }

        /** @var CardClaim $modelClaim */
        $support = CardSupport::find()
            ->where([
                'card_address' => $modelCard->public_address,
                'sig_address' => $supporterAddress,
                'support_id' => DimSupportCard::HUMAN_SUPP_ID
            ])
            ->one();

        if (!empty($support)) {
            return $this->failure("Support are already exist", 422);
        }

        $modelSupport = DraftHumanClaimSupport::findOne(['card_address' => $address]);

        if (!empty($modelSupport)) {
            $modelSupport->delete();
        }


        $modelSupport = new DraftHumanClaimSupport();
        $modelSupport->support_id = DimSupportCard::HUMAN_SUPP_ID;
        $modelSupport->card_address = $address;
        $modelSupport->sig_address = $supporterAddress;
        $modelSupport->hash = $modelClaim->hash;

        $message = $modelSupport->getMessageForHumanClaimSupportSig();
        $modelSupport->save();

        return $this->success(['message' => $message]);
    }

    /**
     * @param $address
     * @return array
     */
    public function actionSupportHumanClaim($address)
    {
        /** @var User $user */
        $user = Yii::$app->getUser()->identity;

        $signature = Yii::$app->request->post('signature');

        if ($signature == null) {
            return $this->failure("Signature can not be empty", 422);
        }

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);
        $ownerCard = $card->getOwnerOfCard();

        if (empty($card)) {
            return $this->failure("Model does not exist");
        }

        /** @var CardClaim $cardClaim */
        $cardClaim = CardClaim::findOne(['card_address' => $card->public_address, 'sig_address' => $card->public_address]);

        if (empty($cardClaim)) {
            return $this->failure("Claim does not exist");
        }

        /** @var Card $claimSupporter */
        $claimSupporter = Card::findOne(['public_address' => $user->userKey->public_address, 'is_revoked' => 0]);

        $draftSupport = DraftHumanClaimSupport::findOne(['card_address' => $address, 'sig_address' => $claimSupporter->public_address]);

        if (empty($draftSupport)) {
            return $this->failure("DraftSupport does not exist", 422);
        }

        if (!Signature::isCanValidate($claimSupporter->public_address, $cardClaim->hash)) {
            return $this->failure("Signature already existed", 422);
        }

        if ($cardClaim->card_address == $claimSupporter->public_address) {
            return $this->failure("You can not support you own card");
        }

        $claimSupport = new CardSupport();
        $claimSupport->card_address = $card->public_address;
        $claimSupport->support_id = DimSupportCard::HUMAN_SUPP_ID;
        $claimSupport->sig_address = $claimSupporter->public_address;

        if (!$claimSupport->validateSig($ownerCard->getId(), $signature, $draftSupport->message)) {
            return $this->failure("Signature is not valid", 422);
        }

        $draftSupport->delete();

        return $this->success($card->getFormattedCard());
    }

    public function actionRevokeSupportSignature($address)
    {
        /** @var Card $card */
        $cardOwner = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        /** @var User $user */
        $user = Yii::$app->getUser()->identity;
        $supporterAddress = $user->userKey->public_address;

        if (empty($cardOwner)) {
            return $this->failure("Card does not exist");
        }

        $cardSupport = CardSupport::findOne(['card_address' => $cardOwner->public_address, 'sig_address' => $supporterAddress]);

        /** @var Signature $signature */
        $signature = Signature::findOne(['public_address' => $cardSupport->sig_address, 'hash' => $cardSupport->hash, 'is_voided' => 0, 'is_revoked' => 0]);

        if ($signature == null) {
            return $this->failure("Signature does not exist");
        }


        if ($cardOwner->public_address == $signature->public_address) {
            return $this->failure("You can not revoke self signature", 401);
        }

        if ($signature->revoked($cardOwner)) {
            $cardSupport->delete();
        }

        return $this->success(true);
    }

    public function actionGetHumanClaimSupports($address)
    {
        $page = Yii::$app->request->get("page", 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        /** @var Card $card */
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($card)) {
            return $this->failure("Card does not exists");
        }

        $card->setPage($page);

        $supporters = $card->getHumanClaimSupports();

        return $this->success($supporters);
    }

    public function actionGetGraph($address)
    {
        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($card)) {
            return $this->failure("Card does not exists");
        }

        $model = NodeValidator::findOne(['user_id' => $card->getOwnerOfCard()->getId()]);

        if (empty($model)) {
            return $this->success();
        }

        return $this->success($model->getFormattedCard());

    }

    public function actionGetGraphNode($address)
    {
        $nodeId = Yii::$app->request->get("node_id");

        /** @var NodeValidator $node */
        $node = NodeValidator::findOne(['user_id' => $nodeId]);

        if (empty($node)) {
            return $this->failure("Node does not exists");
        }

        $card = Card::findOne(['public_address' => $address, 'is_revoked' => 0]);

        if (empty($card)) {
            return $this->failure("Card does not exists");
        }

        /** @var NodeValidator $mainNode */
        $mainNode = NodeValidator::findOne(['user_id' => $card->getOwnerOfCard()->getId()]);

        $result = $mainNode->getLevels($node);

        return $this->success($result);

    }


}