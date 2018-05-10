<?php

namespace app\modules\v1\models\oauth2;

use yii\base\Model;


class OauthRequestToken extends Model
{
    /** @var string */
    public $code;

    /** @var string */
    public $client_id;

    /** @var string */
    public $client_secret;

    /** @var string */
    public $redirect_uri;

    /** @var string */
    public $grant_type;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['code', 'client_id', 'client_secret', 'redirect_uri', 'grant_type'], 'required'],
            [['code', 'client_id', 'client_secret', 'redirect_uri', 'grant_type'], 'string']
        ];
    }
}