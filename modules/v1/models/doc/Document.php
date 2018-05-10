<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\doc;

use app\modules\v1\helpers\FileContentHelper;
use app\modules\v1\models\box\Box;
use app\modules\v1\models\card\Card;
use app\modules\v1\models\forms\UploadDocForm;
use app\modules\v1\models\search\Search;
use app\modules\v1\models\User;
use app\modules\v1\traits\GethClientTrait;
use app\modules\v1\traits\PaginationTrait;
use League\HTMLToMarkdown\HtmlConverter;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;

/**
 * This is the model class for table "document".
 *
 * @property integer $id
 * @property string $title
 * @property string $content
 * @property integer $type
 * @property integer $user_id
 * @property integer $box_id
 * @property integer $is_moved_to_bin
 * @property integer $is_signed
 * @property string $url
 * @property string $hash
 * @property string $nonce
 * @property string $icon
 * @property integer $created_at
 * @property integer $is_encrypted
 * @property integer $is_open_for_sig
 */
class Document extends ActiveRecord implements Search
{

    use PaginationTrait;
    use GethClientTrait;

    const VISIBILITY_PUBLIC = 1;
    const VISIBILITY_PRIVATE = 2;
    const VISIBILITY_CUSTOM = 3;

    const TYPE_CUSTOM = 0;
    const TYPE_HC = 1;

    const ALIGN_LEFT = 'left';
    const ALIGN_RIGHT = 'right';
    const ALIGN_CENTER = 'center';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'document';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'user_id', 'box_id', 'created_at', 'is_moved_to_bin', 'is_signed', 'is_open_for_sig', 'is_encrypted'], 'integer'],
            [['user_id', 'box_id', 'title', 'is_encrypted'], 'required'],
            [['url', 'title', 'nonce'], 'string', 'max' => 255],
            [['hash', 'content', 'icon'], 'string'],
            [['box_id'], 'exist', 'skipOnError' => true, 'targetClass' => Box::className(), 'targetAttribute' => ['box_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPermissions()
    {
        return $this->hasOne(DocumentPermissionSettings::className(), ['doc_id' => 'id']);
    }

    public function formatCard()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->getType(),
            'box_id' => $this->box_id,
            'user_id' => $this->user_id,
            'icon' => $this->getPreviewIcon(),
            'url' => $this->getUrl(),
            'created' => Yii::$app->formatter->asDate($this->created_at),
            'signatures' => $this->getSignatures(),
            'hash' => $this->isContentVisible() ? $this->hash : null,
            'is_open_for_sign' => $this->is_open_for_sig,
            'is_encrypted' => $this->is_encrypted,
            'settings' => $this->getSettings()
        ];
    }

    public function formatCardForUpload()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->getType(),
            'box_id' => $this->box_id,
            'user_id' => $this->user_id,
            'url' => $this->getUrl(),
            'created' => Yii::$app->formatter->asDate($this->created_at),
            'signatures' => $this->getSignaturesForUploadDoc(),
            'hash' => $this->isContentVisible() ? $this->hash : null,
            'is_open_for_sign' => $this->is_open_for_sig,
            'is_encrypted' => $this->is_encrypted,
            'settings' => $this->getSettings()
        ];
    }

    /**
     * @param $field
     * @return bool
     */
    private function checkVisibility($field)
    {
        $userId = isset(Yii::$app->user) ? Yii::$app->user->id : null;

        if ($userId === $this->user_id) {
            return true;
        }

        $boxSettings = DocumentPermissionSettings::find()->where(['doc_id' => $this->id])->all();
        $mapSettings = self::mapSettings();

        $key = array_search($field, $mapSettings, true);

        /** @var DocumentPermissionSettings $setting */
        foreach ($boxSettings as $setting) {
            if ($key == $setting->permission_id) {
                if ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_PUBLIC) {
                    return true;
                } elseif ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_PRIVATE) {
                    return false;
                } elseif ($setting->permission_state == DocumentPermissionSettings::PRIVACY_TYPE_CUSTOM) {
                    $customs = (new Query())
                        ->from('document_custom_permissions')
                        ->where(['custom_id' => $setting->custom_permission_id])
                        ->all();
                    $users = ArrayHelper::getColumn($customs, 'user_id');
                    if (in_array($userId, $users)) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    public function getCustomVisibilityUsers()
    {
        $setting = DocumentPermissionSettings::findOne(['doc_id' => $this->id]);
        $users = ArrayHelper::getColumn(DocumentCustomPermissions::find()->where(['custom_id' => $setting->custom_permission_id])->all(), 'user_id');
        if (!empty($users)) {
            return $users;
        }
        return [];
    }

    public function isMovedToBin()
    {
        $this->is_moved_to_bin = 1;

        if ($this->update()) {
            return true;
        }
        return false;
    }

    public function saveDocument($body, $backup = false, $encrypted = false)
    {
        /** @var User $user */
        $user = Yii::$app->user->identity;


        $fileName = $this->title;

        if ($backup) {
            $fileName = $this->hash . '_backup';
        }


        $temp = tmpfile();
        fwrite($temp, $body);

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        //if userId is null (for book cover, story images)
        $awsPath = $user->id . '/documents/' . $this->id . '/' . $fileName . '.md';

        $bucket = $s3->commands()->upload($awsPath, $temp)->execute();

        if (!empty($bucket)) {
            $fileUrl = $bucket['ObjectURL'];

            if (!$encrypted) {
                $this->url = $fileUrl;
            } else {
                $model = new DocumentEncrypted();
                $model->document_id = $this->id;
                $model->url = $fileUrl;
                $model->save();
            }

            fclose($temp);
            return true;
        }
        return false;
    }

    public function updateDocumentFile($file)
    {
        $s3 = Yii::$app->get('s3');

        $awsPath = explode("/", $this->getUrl());
        $awsPath = array_slice($awsPath, 4);
        $awsPath = implode("/", $awsPath);

        if (!empty($awsPath)) {
            if ($this->is_open_for_sig and !$this->is_encrypted) {
                $mess = FileContentHelper::getContent($this->url);
                $strRStart = strpos($mess, "<?--- START DOCUMENT CONTENT ---?>");
                $length = $strRStart + strlen("<?--- START DOCUMENT CONTENT ---?>") + 2;
                $strREnd = strrpos($mess, "<?--- END DOCUMENT CONTENT ---?>") - strlen($mess) - 2;
                $newMessage = substr_replace($mess, $file, $length, $strREnd);

                $res = $s3->commands()->put($awsPath, $newMessage)->execute();
            } else {
                $res = $s3->commands()->put($awsPath, $file)->execute();
            }

            $this->url = $res["ObjectURL"];
            $this->content = $file;

        } else {
            if ($this->is_open_for_sig) {
                $this->saveDocument($file, true);
            } else {
                $this->saveDocument($file);
            }
        }
    }

    public function validateSign($publicAddress, $signature)
    {
        $message = FileContentHelper::getContent($this->url);
        $messageHex = $this->hexMessage($message . $this->nonce);

        $possibleAddresses = [strtolower($publicAddress)];

        if ($this->verifySig($messageHex, $signature, $possibleAddresses)) {
            $signatureModel = new DocSignature();
            $signatureModel->sig = $signature;
            $signatureModel->document_id = $this->id;
            $signatureModel->public_address = $publicAddress;
            $signatureModel->message = $message;
            $signatureModel->hash = $this->hash;
            $signatureModel->created_at = time();

            $user = User::findOne($this->user_id);

            $awsPath = $user->id . '/documents/' . $this->id . '/signatures/' . $this->hash . '_' . $publicAddress;
            if ($signatureModel->saveFileSignature($awsPath)) {
                return true;
            }

        }

        return false;

    }

    public function getSignatures()
    {
        $result = [];

        $signatures = DocSignature::findAll(['hash' => $this->hash, 'is_voided' => 0]);

        foreach ($signatures as $signature) {
            $result[] = $signature->getFormattedCard();
        }

        return $result;
    }


    public function messageForOpenSig()
    {
        $message = FileContentHelper::getContent($this->url);

        $nonce = \Yii::$app->security->generateRandomString(32);
        $date = Yii::$app->formatter->asDatetime($this->created_at, 'dd-MMM-yyyy HH:MM:ss');
        $nonceMessage = " {$nonce}-nonce-create-timestamp-{$date}";

        $this->nonce = $nonceMessage;

        return $message . $nonceMessage;
    }

    public function messageForSig()
    {
        $message = FileContentHelper::getContent($this->url);

        return $message . $this->nonce;
    }

    public function canRemove()
    {
        if ($this->is_signed) {
            return false;
        }

        return true;
    }

    public function canUpdate()
    {
        if ($this->is_signed) {
            return false;
        }

        return true;
    }

    /**
     * Check if current user can see content of this document
     *
     * @return bool
     */
    public function isContentVisible()
    {
        return $this->checkVisibility('can_see_content');
    }

    /**
     * Check if current user can see content of this document
     *
     * @return bool
     */
    public function isCanSign()
    {
        return $this->checkVisibility('can_sign');
    }


    public function getType()
    {
        if ($this->type == self::TYPE_CUSTOM) {
            return "custom";
        }
    }

    public static function mapSettings()
    {
        $models = DimPermissionDocument::find()->all();
        $map = ArrayHelper::getColumn(ArrayHelper::index($models, 'id'), 'name');
        return $map;
    }

    public function getSettings()
    {
        $data = [];
        $docSettings = DocumentPermissionSettings::find()->where(['doc_id' => $this->id])->all();
        $docSettings = ArrayHelper::index($docSettings, 'permission_id');
        $mapSettings = self::mapSettings();
        foreach ($mapSettings as $key => $setting) {
            $data[$setting] = $docSettings[$key]->permission_state;
        }
        $data['users_array'] = [
            'users_can_see_content' => $docSettings[1]->permission_state == 2 ? DocumentCustomPermissions::getUsers($docSettings[1]->custom_permission_id) : [],
            'users_can_sign' => $docSettings[2]->permission_state == 2 ? DocumentCustomPermissions::getUsers($docSettings[2]->custom_permission_id) : []
        ];

        return $data;
    }

    public function canSign($userId)
    {
        if ($this->user_id == $userId) {
            return true;
        } else {
            if (!$this->is_open_for_sig) {
                return false;
            }

            if ($this->isCanSign()) {
                return true;
            }
            return false;
        }
    }


    /**
     * @param $userId
     * @param Document $doc
     */
    public static function copyToSigned($userId, self $doc)
    {
        $box = Box::findOne(['name' => Box::DEFAULT_SIGNED_BOX, 'user_id' => $userId]);

        $model = new self();
        $model->box_id = $box->id;
        $model->title = $doc->title;
        $model->type = $doc->type;
        $model->user_id = $userId;
        $model->url = $doc->url;
        $model->hash = $doc->hash;
        $model->icon = $doc->icon;
        $model->is_signed = $doc->is_signed;
        $model->is_open_for_sig = $doc->is_open_for_sig;
        $model->is_encrypted = $doc->is_encrypted;

        $model->save();

        $model->saveDocument(FileContentHelper::getContent($model->url), true, true);

        DocumentPermissionSettings::setValues($model->id);

    }

    public function getUrl()
    {
        if ($this->isContentVisible()) {
            if ($this->is_encrypted) {
                $url = $this->checkReceiverUrl();

                return $url;
            }
            return $this->url;
        }
        return null;
    }

    private function checkReceiverUrl()
    {
        return null;
    }

    public function fullDocument()
    {
        $sectionDocs = FileContentHelper::getContent($this->url);

        $signatures = DocSignature::findAll(['hash' => $this->hash, 'is_voided' => 0]);

        $properties = [];

        $template = "<?--- (((((START VALIDBOOK DOCUMENT))))) ---?>\n\n<?--- (((((START TEXT))))) ---?>\n\n";
        $template .= $sectionDocs . "\n\n<?--- (((((END TEXT))))) ---?>\n\n<?--- (((((START PROPERTIES))))) ---?>  \n\n";
        $template .= "<?--- \n";

        $version = getenv("VALIDBOOK_DOC_VERSION");
        $properties['version'] = $version;
        if (!empty($signatures)) {
            $properties['documentNonce'] = $this->nonce;
            foreach ($signatures as $signature) {
                $properties['signatures'][] = [
                    "address" => $signature->public_address,
                    "messageHash" => $signature->hash,
                    "comments" => "#Web3 eth.accounts.sign function was used to create the signature. Message used for this signature was made by appending documentNonce value to the text taken between START TEXT and END TEXT rows of this document.",
                    "signature" => $signature->sig,
                    "signatureCreateTimestamp" => Yii::$app->formatter->asDate($signature->created_at, 'dd MMM yyyy HH:mm:ss')
                ];
            }
        }

        $properties = json_encode($properties, JSON_PRETTY_PRINT);

        $template .= $properties;

        $template .= "\n---?> \n";

        $template .= "\n<?--- (((((END PROPERTIES))))) ---?>\n";

        $template .= "\n<?--- (((((END VALIDBOOK DOCUMENT))))) ---?>\n";

        $temp = tmpfile();
        fwrite($temp, $template);

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        $user = Yii::$app->getUser()->identity;

        $fileName = $this->hash . "_full";
        //if userId is null (for book cover, story images)
        $awsPath = $user->getId() . '/documents/' . $this->id . '/' . $fileName . '.md';

        $bucket = $s3->commands()->upload($awsPath, $temp)->execute();
        if (isset($bucket["ObjectURL"])) {
            return $bucket["ObjectURL"];
        }

        return null;
    }

    public function upload(UploadDocForm $file)
    {
        if ($file->uploadDoc($this)) {
            return true;
        }

        return false;
    }

    private function getSignaturesForUploadDoc()
    {
        $result = [];

        $signatures = DocSignature::findAll(['hash' => $this->hash, 'is_voided' => false]);

        /** @var DocSignature $signature */
        foreach ($signatures as $signature) {

            $ac = Card::findOne(['public_address' => $signature->public_address]);
            /** @var User $user */
            $user = $ac->getOwnerOfCard();

            $valid = $signature->verifySignature();

            $result[] = [
                'id' => $signature->id,
                'public_address' => $signature->public_address,
                'short_format_url' => $signature->short_sig_link,
                'long_format_url' => $signature->long_sig_link,
                'created' => Yii::$app->formatter->asDate($this->created_at),
                'user' => !empty($user) ? $user->getShortFormattedData() : null,
                'is_valid' => $valid
            ];
        }

        return $result;
    }

    public function getSearchResult($q)
    {
        $data = [];

        $docs = self::find()
            ->where(['is_encrypted' => 0, 'is_moved_to_bin' => 0])
            ->andwhere('created_at > unix_timestamp(NOW() - INTERVAL 1 HOUR)')
            ->andWhere("`title` LIKE '$q%'")
            ->orWhere("`content` LIKE '$q%'")
            ->all();
        /** @var self $doc */
        foreach ($docs as $doc) {
            $box = Box::findOne($doc->box_id);
            if ($box->isExistenceVisibile() and $box->isContentVisible() and !$box->isInBin()) {
                if ($doc->isContentVisible()) {
                    $data[] = $doc->formatCard();
                }
            }
        }

        return $data;
    }

    public function getClassName()
    {
        return StringHelper::basename(get_class($this));
    }

    public function setPreviewImage()
    {
        $converter = new HtmlConverter();
        $content = $converter->convert($this->content);
        // Create the image
        $im = imagecreatetruecolor(200, 150);

        // Create a few colors
        $white = imagecolorallocate($im, 255, 255, 255);
        $black = imagecolorallocate($im, 0, 0, 0);
        imagefilledrectangle($im, 0, 0, 200, 200, $white);

        // Replace path by your own font path
        $font = dirname(dirname(dirname(dirname(__DIR__)))) . '/web/fonts/' . 'Roboto-Regular.ttf';

        // And add the text
        $this->imagettftextbox($im, 4, 0, 20, 0, $black, $font, $content, 166, self::ALIGN_LEFT);

        // Using imagepng() results in clearer text compared with imagejpeg()
        imagepng($im, Yii::getAlias("@runtime") . '/' . 'thumb-test-photo.jpg');

        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');

        if ($this->icon == null) {
            $awsPath = $this->user_id . '/documents/previews/' . Yii::$app->security->generateRandomString(6) . '.jpg';

            $bucket = $s3->commands()->upload($awsPath, Yii::getAlias("@runtime") . '/' . 'thumb-test-photo.jpg')->execute();
        } else {
            $path = explode("/", $this->icon);
            $awsPath = implode("/", array_slice($path, 4));

            $bucket = $s3->commands()->upload($awsPath, Yii::getAlias("@runtime") . '/' . 'thumb-test-photo.jpg')->execute();
        }


        if (!empty($bucket)) {
            return $bucket['ObjectURL'];
        }
        imagedestroy($im);
        return null;
    }


    public function getPreviewIcon()
    {
        if ($this->isContentVisible()) {
            if ($this->is_encrypted) {
                $icon = Yii::$app->params["closedDocumentIcon"];

                return $icon;
            }
            return $this->icon;
        }
        return Yii::$app->params["closedDocumentIcon"];
    }

    private function imagettftextbox(&$image, $size, $angle, $left, $top, $color, $font, $text, $max_width, $align)
    {
        $text_lines = explode("\n", $text); // Supports manual line breaks!

        $lines = array();
        $line_widths = array();

        $largest_line_height = 0;

        foreach ($text_lines as $block) {
            $current_line = ''; // Reset current line

            $words = explode(' ', $block); // Split the text into an array of single words

            $first_word = TRUE;

            $last_width = 0;

            for ($i = 0; $i < count($words); $i++) {
                $item = $words[$i];
                $dimensions = imagettfbbox($size, $angle, $font, $current_line . ($first_word ? '' : ' ') . $item);
                $line_width = $dimensions[2] - $dimensions[0];
                $line_height = $dimensions[1] - $dimensions[7];

                if ($line_height > $largest_line_height) $largest_line_height = $line_height;

                if ($line_width > $max_width && !$first_word) {
                    $lines[] = $current_line;

                    $line_widths[] = $last_width ? $last_width : $line_width;

                    $current_line = $item;
                } else {
                    $current_line .= ($first_word ? '' : ' ') . $item;
                }

                if ($i == count($words) - 1) {
                    $lines[] = $current_line;

                    $line_widths[] = $line_width;
                }

                $last_width = $line_width;

                $first_word = FALSE;
            }

            if ($current_line) {
                $current_line = $item;
            }
        }

        $i = 0;
        foreach ($lines as $line) {
            if ($align == self::ALIGN_CENTER) {
                $left_offset = ($max_width - $line_widths[$i]) / 2;
            } elseif ($align == self::ALIGN_RIGHT) {
                $left_offset = ($max_width - $line_widths[$i]);
            } else {
                $left_offset = 0;
            }
            imagettftext($image, $size, $angle, $left + $left_offset, $top + $largest_line_height + ($largest_line_height * $i), $color, $font, $line);
            $i++;
        }

        return $largest_line_height * count($lines);
    }


}