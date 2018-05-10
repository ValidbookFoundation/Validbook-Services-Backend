<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\book;

use app\modules\v1\models\story\StoryBook;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * Class LoggedBook
 * @package app\modules\v1\models
 */
class LoggedBook extends Book
{
    /** @var  boolean */
    public $is_logged = false;

    /**
     * @param $userId
     * @param $storyId
     * @return array
     */
    public static function getFormattedData($userId, $storyId)
    {
        $booksData = [];

        $roots = self::find()
            ->where(['author_id' => $userId])
            ->roots()
            ->all();
        /** @var LoggedBook $root */
        foreach ($roots as $root) {
            $children = $root->childsForLog($root, $storyId);
            $children[] = $root->addBinTree($userId);

            $booksData[] = [
                'name' => $root->name,
                'key' => 'root',
                'show' => true,
                'children' => $children,
            ];
        }
        return $booksData;
    }

    /**
     * @param $node
     * @param $storyId
     * @return array
     */
    protected function childsForLog($node, $storyId)
    {
        if (empty($node)) {
            return [];
        }

        $books = [];
        $children = $node->children(1)->all();
        $storyBooks = StoryBook::findAll(['story_id' => $storyId, 'is_moved_to_bin' => 0]);
        $storyBooksArray = ArrayHelper::getColumn($storyBooks, 'book_id');

        /** @var LoggedBook $child */
        foreach ($children as $child) {
            if (!$child->isInBin()) {
                if (in_array($child->id, $storyBooksArray)) {
                    $child->is_logged = true;
                }
                $bookItem = [
                    'name' => $child->name,
                    'key' => $child->getUrl(),
                    'icon' => $child->getIcon(),
                    'href' => Url::to([\Yii::$app->controller->module->getVersion() . '/books', 'book_slug' => $child->getUrl()], true),
                    'auto_export' => $child->auto_export,
                    'auto_import' => $child->auto_import,
                    'is_logged_story' => $child->is_logged
                ];

                if ($child->is_default == 1) {
                    $wallbook = array_merge($bookItem, [
                        'no_drag' => true,
                        'is_logged_story' => $child->is_logged
                    ]);

                    //add wallbook to the beginning of the array
                    array_unshift($books, $wallbook);
                } else {
                    $children = $this->childsForLog($child, $storyId);

                    $books[] = array_merge($bookItem, [
                        'no_drag' => false,
                        'children' => $children,
                        'is_logged_story' => $child->is_logged
                    ]);
                }
            }
        }

        return $books;
    }

}
