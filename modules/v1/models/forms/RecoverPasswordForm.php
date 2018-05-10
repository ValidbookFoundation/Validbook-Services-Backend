<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;

use app\modules\v1\models\User;
use yii\base\Model;

/**
 * Change password form for current user only
 */
class RecoverPasswordForm extends Model
{
    public $id;
    public $new_password;
    public $confirm_password;
    public $user;


    public function __construct(User $user, $newPassword, $confirmPassword, $config = [])
    {
        $this->new_password = $newPassword;
        $this->confirm_password = $confirmPassword;
        $this->user = $user;

        parent::__construct($config);
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['new_password', 'confirm_password'], 'required'],
            [['new_password', 'confirm_password'], 'string', 'min' => 6],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }


    public function changePassword()
    {
        /** @var User $user */
        $user = $this->user;
        $user->setPassword($this->new_password);

        return $user->save(false);
    }

    public function trashHash()
    {
        /** @var User $user */
        $user = $this->user;
        $user->hash = null;
        if ($user->update()) {
            return true;
        }
        return false;
    }
}