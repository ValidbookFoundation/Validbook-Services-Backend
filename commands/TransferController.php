<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\commands;

use app\modules\v1\models\book\Book;
use app\modules\v1\models\story\StoryPermissionSettings;
use Yii;
use yii\console\Controller;
use yii\db\mssql\PDO;

class TransferController extends Controller
{
    public function actionIndex()
    {
        $connection = Yii::$app->db;

        $sql = "SELECT id, email, first_name, last_name, company_name, occupation, description, linkedin, tw_link,
                       fb_link, skype, FK_country_id, city, website, created, updated,
                       first_name_lat, last_name_lat
                FROM user";
        $users = Yii::$app->olddb->createCommand($sql)->queryAll();

        foreach ($users as $user) {

            if(!empty($user['email']) && !empty($user['first_name']) && !empty($user['last_name'])) {

                $transaction = $connection->beginTransaction();
                try
                {
                    $accessToken = Yii::$app->security->generateRandomString();
                    $authKey = Yii::$app->security->generateRandomString();
                    $time = time();
                    $fullName = $user['first_name_lat'].".".$user['last_name_lat'];

                    $sql = "INSERT INTO user 
                        SET 
                          email = '{$user['email']}',
                          first_name = '{$user['first_name']}',
                          last_name = '{$user['last_name']}',
                          access_token = '{$accessToken}',
                          created_at = '{$user['created']}',
                          updated_at = '{$user['updated']}',
                          role_id = 2,
                          status = 1,
                          auth_key = '{$authKey}',
                          slug = '{$fullName}'
                ";
                    $connection->createCommand($sql)->execute();
                    $lastUserId = $connection->getLastInsertID();
                    $bio = $user['description'] ?: "";

                    $sql = "INSERT INTO profile 
                        SET 
                          user_id = '{$lastUserId}',
                          full_name = '{$fullName}',
                          bio = :bio,
                          occupation = '{$user['occupation']}',
                          company = '{$user['company_name']}',
                          country_id = '{$user['FK_country_id']}',
                          location = '{$user['city']}',
                          twitter = '{$user['tw_link']}',
                          facebook = '{$user['fb_link']}',
                          linkedin = '{$user['linkedin']}',
                          skype = '{$user['skype']}',
                          website = '{$user['website']}'
                ";
                    $connection->createCommand($sql)
                        ->bindParam(":bio", $bio, PDO::PARAM_STR)
                        ->execute();

                    //create default book
                    $rootModel = new Book([
                        'name' => 'root',
                        'author_id' => $lastUserId,
                        'created_at' => time(),
                        'is_root' => 1
                    ]);

                    $rootModel->makeRoot();

                    $parentModel = Book::findOne([
                        'id' => $rootModel->id
                    ]);

                    //transfer books
                    $sqlBooks = "SELECT c.id, c.name, c.description
                             FROM collections c
                             LEFT JOIN collections_owners ON collections_owners.FK_collections_id = c.id
                             WHERE FK_parent_id IS NULL AND collections_owners.	FK_user_id = '{$user['id']}'
                ";
                    $books = Yii::$app->olddb->createCommand($sqlBooks)->queryAll();
                    foreach ($books as $book) {

                        $isDefault = ($book['name'] == "Wallbook") ? 1 : 0;

                        $modelBook = new Book();
                        $modelBook->name = $book['name'];
                        $modelBook->description = $book['description'] ?: '';
                        $modelBook->author_id = $lastUserId;
                        $modelBook->created_at = time();
                        $modelBook->old_book_id = $book['id'];
                        $modelBook->is_default = $isDefault;
                        $modelBook->prependTo($parentModel);

                        $this->recursionBook($modelBook->id, $lastUserId, $book['id']);
                    }

                    //create default channel
                    $channelSlug = self::slugify('Mashup');
                    $sqlInsertChannel = "INSERT INTO channel SET 
                                        name = 'Mashup',
                                        user_id = {$lastUserId},
                                        is_default = 1,
                                        created_at = '{$time}',
                                        updated_at = '{$time}',
                                        slug = '{$channelSlug}'
                ";
                    $connection->createCommand($sqlInsertChannel)->execute();

                    //migrate stories
                    $sqlStories = "SELECT id, description, show_on_stories, loud_in_book, is_visible_in_channels, is_hidden, created 
                               FROM contribution
                               WHERE FK_user_id = '{$user['id']}' AND is_moved_to_bin = 0";
                    $stories = Yii::$app->olddb->createCommand($sqlStories)->queryAll();

                    foreach ($stories as $story) {

                        if(!empty($story['description'])) {

                            $visibilityType = StoryPermissionSettings::PRIVACY_TYPE_PUBLIC;
                            if($story['is_hidden'] == 1)
                                $visibilityType = StoryPermissionSettings::PRIVACY_TYPE_PRIVATE;

                            $sqlInsertStory = "INSERT INTO story
                                       SET 
                                        user_id = '{$lastUserId}',
                                        description = :description,
                                        visibility_type = '{$visibilityType}',
                                        in_storyline = '{$story['show_on_stories']}',
                                        in_channels = '{$story['is_visible_in_channels']}',
                                        in_book = '{$story['loud_in_book']}',
                                        created_at = '{$story['created']}',
                                        updated_at = '{$story['created']}'
                                       ";
                            $connection->createCommand($sqlInsertStory)
                                ->bindParam(":description", $story['description'], PDO::PARAM_STR)
                                ->execute();
                            $lastStoryId = $connection->getLastInsertID();

                            //migrate story images
                            $sqlImages = "SELECT id, is_avatar, title 
                                  FROM  contribution_images
                                  WHERE FK_contribution_id = '{$story['id']}' AND is_avatar = 0
                                  ";
                            $images = Yii::$app->olddb->createCommand($sqlImages)->queryAll();
                            foreach ($images as $image) {
                                $isExternal = (empty($image['title'])) ? 0 : 1;

                                $filePath = Yii::$app->params['oldProjectDirectory']."/cdn/stories_images/".$image['id']."/original.jpg";
                                $fileName = Yii::$app->security->generateRandomString();

                                if(file_exists($filePath)) {
                                    $sqlInsertImage = "INSERT INTO story_image 
                                       SET 
                                        story_id = '{$lastStoryId}',
                                        created_at = '{$time}',
                                        updated_at = '{$time}',
                                        is_external = '{$isExternal}'";
                                    $connection->createCommand($sqlInsertImage)->execute();
                                    $lastImageId = $connection->getLastInsertID();

                                    $bucket = $this->transferAws($filePath, $lastStoryId, $fileName, "stories");

                                    $sqlUpdate = "UPDATE story_image
                                              SET
                                                filename = '{$fileName}',
                                                url = '{$bucket['ObjectURL']}',
                                                etag = '{$bucket['ETag']}'
                                              WHERE id = '{$lastImageId}'
                                              ";
                                    $connection->createCommand($sqlUpdate)->execute();
                                }

                                //migrate story links
                                if($isExternal == 1){
                                    $sqlStoryPages = "SELECT link, title, description, created_at, twitter_author, twitter_avatar
                                                      FROM story_page 
                                                      WHERE FK_contribution_id = '{$story['id']}' 
                                                        AND FK_contribution_image_id = '{$image['id']}'";
                                    $pages = Yii::$app->olddb->createCommand($sqlStoryPages)->queryAll();
                                    if(!empty($pages)) {
                                        foreach($pages as $page) {
                                            $sqlInsertStoryLink = "INSERT INTO story_links
                                                               SET 
                                                                story_id = '{$lastStoryId}',
                                                                image_id = '{$lastImageId}',
                                                                link = '{$page['link']}',
                                                                description = :description,
                                                                title = :title,
                                                                twitter_author = '{$page['twitter_author']}',
                                                                twitter_avatar = '{$page['twitter_avatar']}',
                                                                created_at = '{$page['created_at']}'";
                                            $connection->createCommand($sqlInsertStoryLink)
                                                ->bindParam(":description", $page['description'], PDO::PARAM_STR)
                                                ->bindParam(":title", $page['title'], PDO::PARAM_STR)
                                                ->execute();
                                        }
                                    }
                                }
                            }

                            //migrate video
                            $sqlVideos = "SELECT id, video_hash, created
                                  FROM contribution_video
                                  WHERE FK_contribution_id = '{$story['id']}'";
                            $videos =  Yii::$app->olddb->createCommand($sqlVideos)->queryAll();
                            foreach ($videos as $video) {
                                $sqlInsertVideo = "INSERT INTO story_video
                                           SET 
                                            videosource_id = 1,
                                            story_id = '{$lastStoryId}',
                                            hash = '{$video['video_hash']}',
                                            created_at = '{$video['created']}',
                                            updated_at = '{$video['created']}'";
                                $connection->createCommand($sqlInsertVideo)->execute();
                            }

                            //migrate stories_books
                            $sqlStoriesBooks = "SELECT FK_contribution_id, FK_collections_id, created_at 
                                        FROM contribution_collection 
                                        WHERE FK_contribution_id = '{$story['id']}'";
                            $storiesBook = Yii::$app->olddb->createCommand($sqlStoriesBooks)->queryAll();
                            foreach ($storiesBook as $storyBook) {

                                $sqlOldBookModel = "SELECT id FROM book WHERE old_book_id = '{$storyBook['FK_collections_id']}'";
                                $oldBookModel =  $connection->createCommand($sqlOldBookModel)->queryOne();

                                if($oldBookModel) {
                                    if(!StoryBook::find()->where([
                                        'story_id' => $lastStoryId,
                                        'book_id' => $oldBookModel['id']
                                    ])->exists()) {
                                        $sqlInsertStoryBook = "INSERT INTO story_book
                                               SET
                                                story_id = '{$lastStoryId}',
                                                book_id = '{$oldBookModel['id']}'
                                                created_at = {$storyBook['created_at']}
                                              ";
                                        $connection->createCommand($sqlInsertStoryBook)->execute();
                                    }
                                }
                            }
                        }
                    }

                    //migrate avatars
                    $filePath = Yii::$app->params['oldProjectDirectory']."/cdn/".$user['id']."/avatar/original.jpg";
                    $fileName = Yii::$app->security->generateRandomString();

                    if(file_exists($filePath)) {
                        $bucket = $this->transferAws($filePath, $lastUserId, $fileName, "avatars");

                        $sqlUpdate = "UPDATE profile
                                          SET avatar = '{$bucket['ObjectURL']}'
                                          WHERE user_id = '{$lastUserId}'
                                          ";
                        $connection->createCommand($sqlUpdate)->execute();
                    }

                    echo $user['first_name']." ".$user['last_name']."\n";

                    $transaction->commit();
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }

            }
        }

        //book publicity
        $sql = "SELECT id FROM book";
        $newBooks = Yii::$app->db->createCommand($sql)->queryAll();
        foreach ($newBooks as $book) {
            $sql = "INSERT INTO book_settings SET book_id = '{$book['id']}'";
            $connection->createCommand($sql)->query();
        }
    }

    private function transferAws($filePath, $objectId, $fileName, $type = "stories")
    {
        /** @var \frostealth\yii2\aws\s3\Service $s3 */
        $s3 = Yii::$app->get('s3');
        $awsPath = $type.'/' . date("Y") . '/' . date("m") . '/' . date("d") . '/' . $objectId . '/' . $fileName.'.jpg';

        $result = $s3->commands()->upload($awsPath, $filePath)->execute();

        return $result;
    }

    private function recursionBook($bookId, $userId, $oldBookId)
    {
        $parentModel = Book::findOne([
            'id' => $bookId,
            'author_id' => $userId
        ]);

        $sqlBooks = "SELECT c.id, c.name, c.description
                     FROM collections c
                     LEFT JOIN collections_owners ON collections_owners.FK_collections_id = c.id
                     WHERE FK_parent_id = '{$oldBookId}'
                ";
        $books = Yii::$app->olddb->createCommand($sqlBooks)->queryAll();

        if(!empty($books)) {
            foreach ($books as $subbook) {
                $modelBook = new Book();
                $modelBook->name = $subbook['name'];
                $modelBook->description = $subbook['description'] ?: '';
                $modelBook->author_id = $userId;
                $modelBook->created_at = time();
                $modelBook->old_book_id = $subbook['id'];
                $modelBook->prependTo($parentModel);

                $this->recursionBook($modelBook->id, $userId, $subbook['id']);
            }
        }

        return false;

    }

    static public function slugify($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }
}
