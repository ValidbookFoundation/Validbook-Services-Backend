<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use app\modules\v1\models\book\Book;
use app\modules\v1\models\card\CardSupport;
use app\modules\v1\models\notification\NotificationFactory;
use app\modules\v1\models\redis\CalmReceiver;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\User;
use yii\console\Controller;

class CalmModeSendController extends Controller
{
    public function actionIndex()
    {
        $receiversModels = CalmReceiver::find()->all();
        /** @var CalmReceiver $receiversModel */
        foreach ($receiversModels as $receiversModel) {
            $sender = new User(json_decode($receiversModel->sender));
            switch ($receiversModel->name_object) {
                case 'User':
                    $object = new User(json_decode($receiversModel->object));
                    break;
                case 'Story':
                    $object = new Story(json_decode($receiversModel->object));
                    break;
                case 'Book':
                    $object = new Book(json_decode($receiversModel->object));
                    break;
                case 'CardSupport':
                    $object = new CardSupport(json_decode($receiversModel->object));
            }

            // send notification
            $notBuilder = new NotificationFactory($sender, $receiversModel->receiver_id, $object);
            $receivers = json_decode($receiversModel->receivers, true);
            $notBuilder->addModel($receivers);
            $notBuilder->build();
            $receiversModel->delete();
        }

    }
}