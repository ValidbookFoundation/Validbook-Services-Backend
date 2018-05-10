<?php
/**
 * Created by Validson Team.
 * Author: https://github.com/YuriyBlackFlag
 */


namespace app\modules\v1\models\oauth2;


use OAuth2\Storage\Memory;

/**
 * Class IdToken
 * @package app\modules\v1\models\oauth2
 */
class IdToken
{
    private $_idToken;

    public function __construct($clientId, $scope, $userId, $accessToken, $nonce)
   {
       $config['issuer'] = getenv("API_DOMAIN");

       $publicKey = file_get_contents(getenv("JWSK_PUB"));
       $privateKey = openssl_get_privatekey(getenv("JWSK_PRV"), getenv("JWSK_P"));

       // create storage
       $keyStorage = new Memory(['keys' => [
           $clientId => [
               'public_key' => $publicKey,
               'private_key' => $privateKey,
           ]
       ]
       ]);

       $userClaimStorage = new UserClaim();
       $userClaims = $userClaimStorage->getUserClaims($userId, $scope);

       $idToken = new ResponseIdToken($userClaimStorage, $keyStorage, $config);

       $newIdToken = $idToken->createIdToken(
           $clientId,
           $userId,
           $nonce,
           $userClaims,
           $accessToken);

       $this->setIdToken($newIdToken);

   }

    /**
     * @return mixed
     */
    public function getIdToken()
    {
        return $this->_idToken;
    }

    /**
     * @param mixed $idToken
     */
    public function setIdToken($idToken): void
    {
        $this->_idToken = $idToken;
    }
}