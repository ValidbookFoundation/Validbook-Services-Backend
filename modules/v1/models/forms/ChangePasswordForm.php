<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\forms;

use app\modules\v1\models\User;
use Yii;
use yii\base\Model;

/**
 * Change password form for current user only
 */
class ChangePasswordForm extends Model
{
    public $id;
    public $current_password;
    public $new_password;
    public $confirm_password;

    /**
     * @var \app\modules\v1\models\User
     */
    private $_user;

    /**
     * ChangePasswordForm constructor.
     * @param array $currentPassword
     * @param $newPassword
     * @param $confirmPassword
     * @param array $config
     */
    public function __construct($currentPassword, $newPassword, $confirmPassword, $config = [])
    {
        $this->current_password = $currentPassword;
        $this->new_password = $newPassword;
        $this->confirm_password = $confirmPassword;

        parent::__construct($config);
    }

    /** @return User */
    public function getUser()
    {
        if ($this->_user == null) {
            $this->_user = Yii::$app->user->identity;
        }
        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['current_password', 'new_password','confirm_password'], 'required'],
            [['current_password', 'new_password','confirm_password'], 'string', 'min' => 6],
            ['current_password', function ($attr) {
                if (!$this->user->validatePassword($this->$attr)) {
                    $this->addError($attr, 'Current password is not valid');
                }
            }],
            ['confirm_password', 'compare', 'compareAttribute' => 'new_password'],
        ];
    }

    /**
     * Changes password.
     *
     * @return boolean if password was changed.
     */
    public function changePassword()
    {
        $user = $this->user;
        $user->setPassword($this->new_password);

        return $user->save(false);
    }
}