<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\search;


interface Search
{
    public function getSearchResult($q);

    public function getClassName();
}