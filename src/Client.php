<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce;

use Greenlyst\BaseCommerce\Core\TripleDESService;
use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;

final class Client extends GuzzleClient
{
    private $sdkUsername;
    private $sdkPassword;
    private $sdkKey;
    private $production = false;
    private $tripleDESService;

    const PRODUCTION_URL = 'https://gateway.basecommerce.com';
    const SANDBOX_URL = 'https://gateway.basecommercesandbox.com';
    const USER_AGENT = 'BaseCommerceClientPHP/5.2.00';

    /**
     * Client constructor.
     * @param $sdkUsername
     * @param $sdkPassword
     * @param $sdkKey
     * @param bool $production
     */
    public function __construct($sdkUsername, $sdkPassword, $sdkKey, bool $production = false)
    {
        $this->sdkUsername = $sdkUsername;
        $this->sdkPassword = $sdkPassword;
        $this->sdkKey = $sdkKey;
        $this->production = $production;

        $this->tripleDESService = new TripleDESService($this->getKey());

        parent::__construct([
            'base_uri' => $this->getEndpointURL(),
            'headers' => [
                'user-agent' => self::USER_AGENT
            ]
        ]);
    }


    private function getEndpointURL()
    {
        return $this->production == true ? self::PRODUCTION_URL : self::SANDBOX_URL;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->sdkUsername;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->sdkPassword;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->sdkKey;
    }

    /**
     * @return TripleDESService
     */
    public function getTripleDESService(): TripleDESService
    {
        return $this->tripleDESService;
    }

    public function toArray()
    {
        return [
            'gateway_username' => $this->getUsername(),
            'gateway_password' => $this->getPassword()
        ];
    }

    /**
     * @param $uri
     * @param $data
     * @param int $retryCounter
     *
     * @return array
     *
     * @throws ClientException
     * @throws GuzzleException
     */
    public function postRequest($uri, $data, $retryCounter = 0)
    {
        $response = $this->request('POST', $uri, $data);

        if ($response->getStatusCode() == 403) {
            throw ClientException::invalidCredentials();
        } else if ($response->getStatusCode() == 500) {
            throw ClientException::internalServerError();
        } else if ($response->getStatusCode() == 404) {
            throw ClientException::invalidURLOrHost();
        } else if ($response->getStatusCode() == 400) {
            if ($retryCounter < 10) {
                sleep(3);

                return $this->postRequest($uri, $data, $retryCounter);
            }
        } else if ($response->getStatusCode() == 200) {
            $content = $response->getBody()->getContents();

            $decryptedResponse = $this->getTripleDESService()->decrypt($content);

            $trimmedResponse = trim($decryptedResponse, "\x00..\x1F");

            return json_decode($trimmedResponse, true);
        }
    }
}
