<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/08/2018
 * Time: 00:47
 */

namespace Envato\Service\Api;

/**
 * Class User
 * @package Envato\Service\Api
 */
class User extends AbstractApi
{
    /**
     * User constructor.
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
     * @param string $username
     * @return mixed
     */
    public function accounts(string $username)
    {
        return $this->get($this->getApiUrl() . '/v1/market/user:'. $username . '.json');
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function badges(string $username)
    {
        return $this->get($this->getApiUrl() . '/v1/market/user-badges:'. $username . '.json');
    }

    /**
     * @param string $username
     * @return mixed
     */
    public function itemsBySite(string $username)
    {
        return $this->get($this->getApiUrl() . '/v1/market/user-items-by-site:'. $username . '.json');
    }

    /**
     * @param string $username
     * @param string $site
     * @return mixed
     */
    public function newItems(string $username, string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/new-files-from-user:'. $username . ',' . $site . '.json');
    }
}