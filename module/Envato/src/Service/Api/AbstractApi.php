<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 06/08/2018
 * Time: 23:54
 */

namespace Envato\Service\Api;

use Curl\Curl;
use Carbon\Carbon;

/**
 * Class AbstractApi
 * @package Envato\Service\Api
 */
abstract class AbstractApi
{
    /** @var string $apiUrl */
    private $apiUrl = 'https://api.envato.com';

    /** @var Curl $curlClient */
    private $curlClient;

    /** @var string $clientId */
    private $clientId;

    /** @var string $clientSecret */
    private $clientSecret;

    /** @var string $accessToken */
    private $accessToken;

    /** @var string $refreshToken */
    private $refreshToken;

    /** @var \DateTime|null $expires */
    private $expires;

    /**
     * AbstractApi constructor.
     * @param string $clientId
     * @param string $clientSecret
     * @param string $accessToken
     * @param string $refreshToken
     * @param null $expires
     */
    public function __construct(
        string $clientId = '',
        string $clientSecret = '',
        string $accessToken = '',
        string $refreshToken = '',
        $expires = null
    )
    {
        $this->setClientId($clientId);
        $this->setClientSecret($clientSecret);
        $this->setAccessToken($accessToken);
        $this->setRefreshToken($refreshToken);
        $this->setExpires($expires);

        // We want to set the curl client last so we can use the access token etc...
        $this->buildCurlClient();
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    protected function get(string $endpoint, array $data = [])
    {
        return $this->doApiRequest($endpoint, 'GET', $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    protected function post(string $endpoint, array $data = [])
    {
        return $this->doApiRequest($endpoint, 'POST', $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    protected function put(string $endpoint, array $data = [])
    {
        return $this->doApiRequest($endpoint, 'PUT', $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    protected function patch(string $endpoint, array $data = [])
    {
        return $this->doApiRequest($endpoint, 'PATCH', $data);
    }

    /**
     * @param string $endpoint
     * @param array $data
     * @return mixed
     */
    protected function delete(string $endpoint, array $data = [])
    {
        return $this->doApiRequest($endpoint, 'DELETE', $data);
    }

    /**
     * Sets the curl client.
     */
    public function buildCurlClient()
    {
        /** @var Curl $curl */
        $curl = new Curl();
        $curl->setUserAgent('ConverseKitDev');
        $this->setCurlClient($curl);
        $this->setBearerTokenHeader();
    }

    /**
     * @param string $endpoint
     * @param string $method
     * @param array $data
     * @return mixed
     */
    private function doApiRequest(string $endpoint, string $method, array $data = [])
    {
        switch (strtolower($method)) {
            case 'get':
                if (!empty($data)) {
                    $this->getCurlClient()->get($endpoint. '?' . http_build_query($data));
                } else {
                    $this->getCurlClient()->get($endpoint);
                }
                break;

            case 'post':
                if (!empty($data)) {
                    $this->getCurlClient()->post($endpoint, $data);
                } else {
                    $this->getCurlClient()->post($endpoint);
                }
                break;

            case 'put':
                if (!empty($data)) {
                    $this->getCurlClient()->put($endpoint, $data);
                } else {
                    $this->getCurlClient()->put($endpoint);
                }
                break;

            case 'patch':
                if (!empty($data)) {
                    $this->getCurlClient()->patch($endpoint, $data);
                } else {
                    $this->getCurlClient()->patch($endpoint);
                }
                break;

            case 'delete':
                if (!empty($data)) {
                    $this->getCurlClient()->delete($endpoint, [], $data);
                } else {
                    $this->getCurlClient()->delete($endpoint);
                }
                break;
        }

        return json_decode(json_encode($this->getCurlClient()->response), true);
    }

    /**
     * Authenticates using the personal token
     * (known as client secret in this module).
     *
     * @param string $personalToken
     */
    public function authenticatePersonal(string $personalToken = '')
    {
        if (!empty($personalToken)) {
            $this->setAccessToken($personalToken);
        }

        // Change the access token to our client secret key
        $this->setAccessToken($this->getClientSecret());
    }

    /**
     * Authenticates using oauth access token or refresh token.
     *
     * @param string $accessToken
     * @param string $refreshToken
     * @param \DateTime $expires
     */
    public function authenticate(string $accessToken, string $refreshToken, \DateTime $expires)
    {
        $this
            ->setAccessToken($accessToken)
            ->setRefreshToken($refreshToken)
            ->setExpires($expires);

        if ($this->isAccessTokenExpired()) {
            $response = $this->getAccessTokenUsingRefreshToken();

            $this
                ->setAccessToken($response['access_token'])
                ->setExpires($this->timestampToDateTime($response['expires_in']));
        }
    }

    /**
     * @return mixed
     */
    private function getAccessTokenUsingRefreshToken()
    {
        $authSecret = base64_encode($this->getClientId() . ':' . $this->getClientSecret());
        $this->getCurlClient()->setHeader('Authorization', 'Basic ' . $authSecret);

        return $this->post('https://api.envato.com/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $this->getRefreshToken(),
            'client_id' => $this->getClientId(),
            'client_secret' => $this->getClientSecret(),
        ]);
    }

    /**
     * @return bool
     */
    private function isAccessTokenExpired(): bool
    {
        if ($this->getExpires()->getTimestamp() < time()) {
            return true;
        }

        return false;
    }

    /**
     * Simply sets the authorization header to use the bearer token.
     */
    private function setBearerTokenHeader()
    {
        $this->getCurlClient()->setHeader('Authorization', 'Bearer ' . $this->getAccessToken());
    }

    /**
     * @param int $timestamp
     * @return \DateTime
     */
    private function timestampToDateTime(int $timestamp): \DateTime
    {
        return Carbon::createFromTimestamp($timestamp);
    }

    # --------------------------------------------------------------------
    # GETTERS AND SETTERS
    # --------------------------------------------------------------------

    /**
     * @return Curl
     */
    protected function getCurlClient(): Curl
    {
        return $this->curlClient;
    }

    /**
     * @param Curl $curlClient
     * @return $this
     */
    protected function setCurlClient(Curl $curlClient)
    {
        $this->curlClient = $curlClient;
        return $this;
    }

    /**
     * @return string
     */
    protected function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     * @return $this
     */
    protected function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    protected function getClientSecret(): string
    {
        return $this->clientSecret;
    }

    /**
     * @param string $clientSecret
     * @return $this
     */
    protected function setClientSecret(string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * @return string
     */
    public function getAccessToken(): string
    {
        return $this->accessToken;
    }

    /**
     * @param string $accessToken
     * @return $this
     */
    protected function setAccessToken(string $accessToken)
    {
        $this->accessToken = $accessToken;
        return $this;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refreshToken;
    }

    /**
     * @param string $refreshToken
     * @return $this
     */
    protected function setRefreshToken(string $refreshToken)
    {
        $this->refreshToken = $refreshToken;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getExpires()
    {
        return $this->expires;
    }

    /**
     * @param \DateTime|null $expires
     * @return $this
     */
    protected function setExpires($expires)
    {
        $this->expires = $expires;
        return $this;
    }

    /**
     * @return string
     */
    protected function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    /**
     * @param string $apiUrl
     * @return $this
     */
    protected function setApiUrl(string $apiUrl)
    {
        $this->apiUrl = $apiUrl;
        return $this;
    }
}