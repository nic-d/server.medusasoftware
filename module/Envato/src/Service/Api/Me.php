<?php
/**
 * Created by PhpStorm.
 * User: Nic
 * Date: 07/08/2018
 * Time: 00:35
 */

namespace Envato\Service\Api;

/**
 * Class Me
 * @package Envato\Service\Api
 */
class Me extends AbstractApi
{
    /**
     * Me constructor.
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
     * @return mixed
     */
    public function accounts()
    {
        return $this->get($this->getApiUrl() . '/v1/market/private/user/account.json');
    }

    /**
     * @param int $page
     * @return mixed
     */
    public function sales(int $page = 1)
    {
        return $this->get($this->getApiUrl() . '/v3/market/author/sales', [
            'page' => $page,
        ]);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function sale($code)
    {
        return $this->get($this->getApiUrl() . '/v3/market/author/sale', [
            'code' => $code,
        ]);
    }

    /**
     * @return mixed
     */
    public function username()
    {
        return $this->get($this->getApiUrl() . '/v1/market/private/user/username.json');
    }

    /**
     * @return mixed
     */
    public function email()
    {
        return $this->get($this->getApiUrl() . '/v1/market/private/user/email.json');
    }

    /**
     * @return mixed
     */
    public function earningsAndSalesByMonth()
    {
        return $this->get($this->getApiUrl() . '/v1/market/private/user/earnings-and-sales-by-month.json');
    }

    /**
     * @param null $filter
     * @param int $page
     * @return mixed
     */
    public function purchaseList($filter = null, int $page = 1)
    {
        $filter = $filter ?? '';

        return $this->get($this->getApiUrl() . '/v3/market/buyer/list-purchases', [
            'filter' => $filter,
            'page'   => $page,
        ]);
    }

    /**
     * @param $id
     * @param $purchaseCode
     * @param bool $shortenUrl
     * @return mixed
     */
    public function buyerDownload($id, $purchaseCode, $shortenUrl = false)
    {
        $shortenUrl = $shortenUrl ? 'true' : 'false';

        return $this->get($this->getApiUrl() . '/v3/market/buyer/download', [
            'item_id' => $id,
            'purchase_code' => $purchaseCode,
            'shorten_url' => $shortenUrl,
        ]);
    }

    /**
     * @param $code
     * @return mixed
     */
    public function buyerPurchase($code)
    {
        return $this->get($this->getApiUrl() . '/v3/market/buyer/purchase', [
            'code' => $code,
        ]);
    }

    /**
     * @param int $page
     * @param null|string $fromDate
     * @param null|string $toDate
     * @param null|string $type
     * @param null|string $site
     * @return mixed
     */
    public function statements(int $page = 1, $fromDate = null, $toDate = null, $type = null, $site = null)
    {
        return $this->get($this->getApiUrl() . '/v3/market/user/statement', [
            'page'      => $page,
            'from_date' => $fromDate,
            'to_date'   => $toDate,
            'type'      => $type,
            'site'      => $site,
        ]);
    }

    /**
     * @return mixed
     */
    public function bookmarks()
    {
        return $this->get($this->getApiUrl() . '/v3/market/user/bookmarks');
    }

    /**
     * @param $id
     * @return mixed
     */
    public function collection($id)
    {
        return $this->get($this->getApiUrl() . '/v3/market/user/collection', [
            'id' => $id,
        ]);
    }
}