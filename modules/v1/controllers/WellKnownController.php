<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\controllers;

use app\modules\v1\components\GuestRestController as Controller;

class WellKnownController extends Controller
{
    public function actionProviderInfo()
    {
        return $this->shortSuccess([
            "issuer" => getenv("API_DOMAIN"),
            "authorization_endpoint" => getenv('SITE_URL') . "/authorize",
            "token_endpoint" => getenv('API_DOMAIN') . "/v1/auth/authorize-token",
            "userinfo_endpoint" => getenv('API_DOMAIN') . "/v1/client/user-info",
            "scopes_supported" => [
                "openid",
                "user_info"
            ],
            "token_endpoint_auth_methods_supported" => ["client_secret_basic"],
            "jwks_uri" => getenv("API_DOMAIN") . "/jwks-keys"
        ], 200);
    }

    public function actionServicesInfo()
    {
        return $this->success([
            "wiki" => getenv("WIKI_URL"),
            "forum" => getenv("FORUM_URL"),
            "drive" => getenv("DRIVE_URL"),
        ]);
    }
}