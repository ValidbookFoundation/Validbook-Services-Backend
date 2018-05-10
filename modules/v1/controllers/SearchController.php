<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\UserRestController as Controller;
use app\modules\v1\models\book\Book;
use app\modules\v1\models\doc\Document;
use app\modules\v1\models\search\GlobalSearch;
use app\modules\v1\models\search\SearchBook;
use app\modules\v1\models\search\SearchDocument;
use app\modules\v1\models\search\SearchFollow;
use app\modules\v1\models\search\SearchStory;
use app\modules\v1\models\search\SearchUser;
use app\modules\v1\models\story\Story;
use app\modules\v1\models\User;
use Yii;

/**
 * Class SearchController
 * @package app\modules\v1\controllers
 */
class SearchController extends Controller
{
    public function actionIndex()
    {
        $q = Yii::$app->request->get('q');

        if ($q !== null) {

            $searchModel = new GlobalSearch($q);

            $searchBook = new SearchBook();
            $searchBook->setPage(1);
            $searchBook->setItemsPerPage(2);
            $searchNewBook = new Book();
            $searchNewBook->setPage(1);
            $searchNewBook->setItemsPerPage(2);
            $searchFollow = new SearchFollow();
            $searchFollow->setPage(1);
            $searchFollow->setItemsPerPage(6);
            $searchUser = new SearchUser();
            $searchUser->setPage(1);
            $searchUser->setItemsPerPage(6);
            $searchNewUser = new User();
            $searchNewUser->setPage(1);
            $searchNewUser->setItemsPerPage(6);

            $searchModel->addSearchModel($searchFollow);
            $searchModel->addSearchModel($searchUser);
            $searchModel->addSearchModel($searchNewUser);
            $searchModel->addSearchModel($searchBook);
            $searchModel->addSearchModel($searchNewBook);

            $data = $searchModel->getSearchResult();

            return $this->success($data);
        } else {
            return $this->failure("Request is empty", 400);
        }
    }

    public function actionAllTab()
    {
        $q = Yii::$app->request->get('q');
        $page = Yii::$app->request->get('page', 1);

        if (!is_numeric($page)) {
            return $this->failure("Invalid parameter 'page'", 400);
        }

        if ($q == null) {
            return $this->failure("Request is empty", 400);
        }

        $searchModel = new GlobalSearch($q);
        $searchModel->setPage(1);

        $searchBook = new SearchBook();
        $searchBook->setPage($page);
        $searchBook->setItemsPerPage(4);
        $searchNewBook = new Book();
        $searchBook->setPage($page);
        $searchBook->setItemsPerPage(4);

        $searchFollow = new SearchFollow();
        $searchFollow->setPage($page);
        $searchFollow->setItemsPerPage(6);
        $searchUser = new SearchUser();
        $searchUser->setPage($page);
        $searchUser->setItemsPerPage(6);
        $searchNewUser = new User();
        $searchNewUser->setPage($page);
        $searchNewUser->setItemsPerPage(6);

        $searchStory = new SearchStory();
        $searchStory->setPage($page);
        $searchStory->setItemsPerPage(6);
        $searchNewStory = new Story();
        $searchNewStory->setPage($page);
        $searchNewStory->setItemsPerPage(6);

        $searchModel->addSearchModel($searchFollow);
        $searchModel->addSearchModel($searchUser);
        $searchModel->addSearchModel($searchNewUser);

        $searchModel->addSearchModel($searchBook);
        $searchModel->addSearchModel($searchNewBook);

        $searchModel->addSearchModel($searchStory);
        $searchModel->addSearchModel($searchNewStory);

        $data = $searchModel->getSearchResult();

        return $this->success($data);
    }

    public function actionBooks()
    {
        $q = Yii::$app->request->get('q');
        $page = (int)Yii::$app->request->get('page', 1);

        if ($q !== null) {
            $searchModel = new GlobalSearch($q);
            $searchModel->setPage($page);

            $searchBook = new SearchBook();
            $searchNewBook = new Book();

            $searchModel->addSearchModel($searchBook);
            $searchModel->addSearchModel($searchNewBook);

            $data = $searchModel->getSearchResult();

            return $this->success($data);
        } else {
            return $this->failure("Request is empty", 400);
        }
    }

    public function actionStories()
    {
        $q = Yii::$app->request->get('q');
        $page = (int)Yii::$app->request->get('page', 1);

        if ($q !== null) {
            $searchModel = new GlobalSearch($q);
            $searchModel->setPage($page);

            $searchStory = new SearchStory();
            $searchNewStory = new Story();

            $searchModel->addSearchModel($searchStory);
            $searchModel->addSearchModel($searchNewStory);

            $data = $searchModel->getSearchResult();

            return $this->success($data);
        } else {
            return $this->failure("Request is empty", 400);
        }
    }

    public function actionUsers()
    {
        $q = Yii::$app->request->get('q');
        $page = (int)Yii::$app->request->get('page', 1);

        if ($q !== null) {
            $searchModel = new GlobalSearch($q);
            $searchModel->setPage($page);

            $searchFollow = new SearchFollow();
            $searchModel->addSearchModel($searchFollow);

            $searchUser = new SearchUser();
            $searchModel->addSearchModel($searchUser);

            $searchNewUser = new User();
            $searchModel->addSearchModel($searchNewUser);

            $data = $searchModel->getSearchResult();

            return $this->success($data);
        } else {
            return $this->failure("Request is empty", 400);
        }
    }

    public function actionDocuments()
    {
        $q = Yii::$app->request->get('q');
        $page = (int)Yii::$app->request->get('page', 1);

        if ($q !== null) {
            $searchModel = new GlobalSearch($q);
            $searchModel->setPage($page);

            $searchDocument = new SearchDocument();
            $searchModel->addSearchModel($searchDocument);

            $searchDoc = new Document();
            $searchModel->addSearchModel($searchDoc);

            $data = $searchModel->getSearchResult();

            return $this->success($data);
        } else {
            return $this->failure("Request is empty", 400);
        }
    }
}