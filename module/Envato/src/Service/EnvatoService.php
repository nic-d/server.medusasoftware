<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/08/2018
 * Time: 21:32
 */

namespace Envato\Service;

use Envato\Service\Api;

/**
 * Class EnvatoService
 * @package Envato\Service
 */
class EnvatoService extends Api\AbstractApi
{
    /**
     * EnvatoService constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $accessToken
     * @param string $refreshToken
     * @param null $expires
     * @throws \ErrorException
     */
    public function __construct(
        string $clientId = '',
        string $clientSecret = '',
        string $accessToken = '',
        string $refreshToken = '',
        $expires = null
    )
    {
        parent::__construct($clientId, $clientSecret, $accessToken, $refreshToken, $expires);
    }

    /**
     * @param string $name
     * @return Api\Market|Api\Me|Api\User
     * @throws \ErrorException
     */
    public function api(string $name)
    {
        $constructorArgs = [
            $this->getClientId(),
            $this->getClientSecret(),
            $this->getAccessToken(),
            $this->getRefreshToken(),
            $this->getExpires(),
        ];

        switch ($name) {
            case 'market':
            case 'markets':
                $class = new Api\Market(...$constructorArgs);
                break;

            case 'me':
                $class = new Api\Me(...$constructorArgs);
                break;

            case 'user':
            case 'users':
                $class = new Api\User(...$constructorArgs);
                break;

            default:
                throw new \Exception('Unknown class! You tried to call: ' . $name);
        }

        return $class;
    }
}