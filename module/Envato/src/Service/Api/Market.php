<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/08/2018
 * Time: 00:26
 */

namespace Envato\Service\Api;

/**
 * Class Market
 * @package Envato\Service\Api
 */
class Market extends AbstractApi
{
    /**
     * Market constructor.
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
     * @param $id
     * @return mixed
     */
    public function collection($id)
    {
        return $this->get($this->getApiUrl() . '/v3/market/catalog/collection', [
            'id' => $id,
        ]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function item($id)
    {
        return $this->get($this->getApiUrl() . '/v3/market/catalog/item', [
            'id' => $id,
        ]);
    }

    /**
     * @param string $site
     * @return mixed
     */
    public function popularItems(string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/popular:' . $site . '.json');
    }

    /**
     * @param string $site
     * @return mixed
     */
    public function categories(string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/categories:' . $site . '.json');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function itemPrices($id)
    {
        return $this->get($this->getApiUrl() . '/v1/market/item-prices:' . $id . '.json');
    }

    /**
     * @param string $site
     * @param string $category
     * @return mixed
     */
    public function newItems(string $site, string $category)
    {
        return $this->get($this->getApiUrl() . '/v1/market/new-files:' . $site . ',' . $category . '.json');
    }

    /**
     * @param string $site
     * @return mixed
     */
    public function features(string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/features:' . $site . '.json');
    }

    /**
     * @param string $site
     * @return mixed
     */
    public function randomNewItems(string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/random-new-files:' . $site . '.json');
    }

    /**
     * @param array $searchQuery
     * @return mixed
     */
    public function search(array $searchQuery = [])
    {
        return $this->get($this->getApiUrl() . '/v1/discovery/search/search/item', $searchQuery);
    }

    /**
     * @return mixed
     */
    public function totalUsers()
    {
        return $this->get($this->getApiUrl() . '/v1/market/total-users.json');
    }

    /**
     * @return mixed
     */
    public function totalItems()
    {
        return $this->get($this->getApiUrl() . '/v1/market/total-items.json');
    }

    /**
     * @param string $site
     * @return mixed
     */
    public function numberOfItemsInCategory(string $site)
    {
        return $this->get($this->getApiUrl() . '/v1/market/number-of-files:' . $site . '.json');
    }
}