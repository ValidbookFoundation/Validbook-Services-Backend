<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */

namespace app\modules\v1\models\oauth2;


use app\modules\v1\models\User;
use OAuth2\OpenID\Storage\UserClaimsInterface;

class UserClaim implements UserClaimsInterface
{

    const SCOPE = 'user_info';

    /**
     * Return claims about the provided user id.
     *
     * Groups of claims are returned based on the requested scopes. No group
     * is required, and no claim is required.
     *
     * @param mixed $user_id - The id of the user for which claims should be returned.
     * @param string $scope - The requested scope.
     * Scopes with matching claims: profile, email, address, phone.
     *
     * @return array - An array in the claim => value format.
     *
     * @see http://openid.net/specs/openid-connect-core-1_0.html#ScopeClaims
     */
    public function getUserClaims($user_id, $scope)
    {
        $arrayScope = explode(' ', $scope);
        if (in_array(self::SCOPE, $arrayScope)) {
            $user = User::findOne($user_id);
            return [
                'email' => $user->getEmail(),
                'name' => $user->getFullName(),
                'slug' => $user->slug
            ];
        }
        return [];
    }
}