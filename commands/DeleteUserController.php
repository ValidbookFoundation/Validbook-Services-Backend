<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;


use PDO;
use Yii;
use yii\console\Controller;
use yii\helpers\ArrayHelper;


class DeleteUserController extends Controller
{

    public function actionIndex()
    {
        $connection = Yii::$app->db;

        $sql = "SELECT user_id
                  FROM deactivate_user
                  WHERE time_expired < UNIX_TIMESTAMP(NOW())";

        $users = ArrayHelper::getColumn(Yii::$app->db->createCommand($sql)->queryAll(), 'user_id');
        if (!empty($users)) {
            $users = implode(",", $users);
            $sqDelete = "DELETE FROM avatar_size WHERE user_id IN ('{$users}');

DELETE FROM book_custom_permissions
WHERE custom_id IN (SELECT bp.custom_permission_id
                    FROM book_permission_settings bp
                    WHERE book_id IN
                          (SELECT book.id
                           FROM book
                             INNER JOIN user
                               ON book.author_id = user.id
                           WHERE user.id IN ('{$users}')));

DELETE FROM follow_book
WHERE book_id IN (SELECT book.id
                  FROM book
                    INNER JOIN user ON book.author_id = user.id
                  WHERE user.id IN ('{$users}'));

DELETE FROM knock_book
WHERE book_id IN (SELECT book.id
                  FROM book
                    INNER JOIN user ON book.author_id = user.id
                  WHERE user.id IN ('{$users}'));

DELETE FROM story_book
WHERE book_id IN (SELECT book.id
                  FROM book
                    INNER JOIN user ON book.author_id = user.id
                  WHERE user.id IN ('{$users}'));

DELETE FROM knock_book
WHERE user_id IN ('{$users}');


DELETE FROM book_permission_settings
WHERE book_id IN (SELECT book.id
                  FROM book
                    INNER JOIN user ON book.author_id = user.id
                  WHERE user.id IN ('{$users}'));
DELETE FROM book
WHERE book.author_id IN ('{$users}');

DELETE FROM signature
WHERE document_id IN (SELECT document.id
                      FROM document
                        INNER JOIN user
                          ON document.user_id = user.id
                        INNER JOIN box
                          ON document.box_id = box.id
                      WHERE user.id IN ('{$users}'));

DELETE FROM box_custom_permissions
WHERE custom_id IN (SELECT bp.custom_permission_id
                    FROM box_permission_settings bp
                    WHERE box_id IN
                          (SELECT box.id
                           FROM box
                             INNER JOIN user
                               ON box.user_id = user.id
                           WHERE user.id IN ('{$users}')));
                           
DELETE FROM box_permission_settings
WHERE box_id IN (SELECT box.id
                 FROM box
                   INNER JOIN user ON box.user_id = user.id
                 WHERE user.id IN ('{$users}'));


DELETE FROM human_card
WHERE box_id IN (SELECT box.id
                 FROM box
                   INNER JOIN user ON box.user_id = user.id
                 WHERE user.id IN ('{$users}'));
DELETE FROM box
WHERE box.user_id IN ('{$users}');

DELETE FROM follow
WHERE user_id IN ('{$users}');

DELETE FROM follow
WHERE followee_id IN ('{$users}');

DELETE FROM channel
WHERE user_id IN ('{$users}');

DELETE FROM comment
WHERE created_by IN ('{$users}');


DELETE FROM document_file
WHERE doc_id IN (SELECT document.id
                 FROM document
                   INNER JOIN user
                     ON document.user_id = user.id
                 WHERE user.id IN ('{$users}'));
                 
DELETE FROM document_custom_permissions
WHERE custom_id IN (SELECT bp.custom_permission_id
                    FROM document_permission_settings bp
                    WHERE doc_id IN
                          (SELECT document.id
                           FROM document
                             INNER JOIN user
                               ON document.user_id = user.id
                           WHERE user.id IN ('{$users}')));
                           
DELETE FROM document_permission_settings
WHERE doc_id IN (SELECT document.id
                 FROM document
                   INNER JOIN user ON document.user_id = user.id
                 WHERE user.id IN ('{$users}'));
                 
DELETE FROM document
WHERE document.user_id IN ('{$users}');

DELETE FROM draft_human_card
WHERE user_id IN ('{$users}');

DELETE FROM follow_book
WHERE user_id IN ('{$users}');

DELETE FROM `like`
WHERE sender_id IN ('{$users}');

DELETE FROM `like`
WHERE story_id IN (SELECT story_id
                   FROM story
                     INNER JOIN user u ON story.user_id = u.id
                   WHERE u.id IN ('{$users}'));

DELETE FROM notification
WHERE sender_id IN ('{$users}');

DELETE FROM notification
WHERE receiver_id IN ('{$users}');

DELETE FROM notification_settings
WHERE user_id IN ('{$users}');

DELETE FROM profile
WHERE user_id IN ('{$users}');


DELETE FROM story_custom_permissions
WHERE custom_id IN (SELECT bp.custom_permission_id
                    FROM story_permission_settings bp
                    WHERE story_id IN
                          (SELECT story.id
                           FROM story
                             INNER JOIN user
                               ON story.user_id = user.id
                           WHERE user.id IN ('{$users}')));
                           
DELETE FROM story_permission_settings
WHERE story_id IN (SELECT story.id
                   FROM story
                     INNER JOIN user ON story.user_id = user.id
                   WHERE user.id IN ('{$users}'));

DELETE FROM story_files
WHERE story_id IN (SELECT story.id
                   FROM story
                     INNER JOIN user ON story.user_id = user.id
                   WHERE user.id IN ('{$users}'));

DELETE FROM story_image
WHERE story_id IN (SELECT story.id
                   FROM story
                     INNER JOIN user ON story.user_id = user.id
                   WHERE user.id IN ('{$users}'));

DELETE FROM story
WHERE user_id IN ('{$users}');

DELETE FROM user_auth
WHERE user_id IN ('{$users}');

DELETE FROM user_photo
WHERE user_id IN ('{$users}');

DELETE FROM user
WHERE id IN ('{$users}');

DELETE FROM deactivate_user
WHERE time_expired < UNIX_TIMESTAMP(NOW());
        ";

            $connection->createCommand($sqDelete)
                ->bindParam(":users", $users, PDO::PARAM_STR)
                ->execute();
        }
    }
}