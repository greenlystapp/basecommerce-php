<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce;

use Greenlyst\BaseCommerce\Core\TripleDESService;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

final class Client
{
    private $sdkUsername;
    private $sdkPassword;
    private $sdkKey;
    private $production = false;
    private $tripleDESService;
    private $sessionId;

    private const PRODUCTION_URL = 'https://gateway.basecommerce.com';
    private const SANDBOX_URL = 'https://gateway.basecommercesandbox.com';
    private const USER_AGENT = 'BaseCommerceClientPHP/5.2.00';

    private const URI_VALIDATE_PING = '/pcms/?f=API_PingPong';

    /**
     * Client constructor.
     *
     * @param      $sdkUsername
     * @param      $sdkPassword
     * @param      $sdkKey
     * @param bool $production
     */
    public function __construct($sdkUsername, $sdkPassword, $sdkKey, bool $production = false)
    {
        $this->sdkUsername = $sdkUsername;
        $this->sdkPassword = $sdkPassword;
        $this->sdkKey = $sdkKey;
        $this->production = $production;

        $this->tripleDESService = new TripleDESService($this->getKey());
    }

    /**
     * @param     $uri
     * @param     $data
     * @param int $retryCounter
     *
     * @throws ClientException
     * @throws GuzzleException
     *
     * @return array
     */
    public function postRequest($uri, $data, $retryCounter = 0)
    {
        $data = array_merge($this->toArray(), [
            'payload' => $this->getTripleDESService()->encrypt((string) $data),
        ]);

        $response = $this->sendRequest($uri, $data);

        if ($response->getStatusCode() !== 200) {
            $this->checkErrorsAndRetryRequest($response, $retryCounter, $uri, $data);
        }

        return $this->processResponse($response, $retryCounter, $uri, $data);
    }

    /**
     * @throws ClientException
     * @throws GuzzleException
     */
    public function validateCredentials()
    {
        $this->postRequest(self::URI_VALIDATE_PING, ['PING' => 'Ping Ping']);

        return true;
    }

    /**
     * @return mixed
     */
    public function getSessionId()
    {
        return $this->sessionId;
    }

    /**
     * @return string
     */
    private function getEndpointURL()
    {
        return $this->production == true ? self::PRODUCTION_URL : self::SANDBOX_URL;
    }

    /**
     * @return mixed
     */
    private function getUsername()
    {
        return $this->sdkUsername;
    }

    /**
     * @return mixed
     */
    private function getPassword()
    {
        return $this->sdkPassword;
    }

    /**
     * @return mixed
     */
    private function getKey()
    {
        return $this->sdkKey;
    }

    /**
     * @return TripleDESService
     */
    private function getTripleDESService(): TripleDESService
    {
        return $this->tripleDESService;
    }

    private function toArray()
    {
        return [
            'gateway_username' => $this->getUsername(),
            'gateway_password' => $this->getPassword(),
        ];
    }

    /**
     * @param $uri
     * @param $data
     *
     * @throws GuzzleException
     *
     * @return ResponseInterface
     */
    private function sendRequest($uri, $data)
    {
        $client = new \GuzzleHttp\Client([
            'base_uri' => $this->getEndpointURL(),
        ]);

        $response = $client->request('POST', $uri, [
            'body' => json_encode($data),
        ]);

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @param                   $retryCounter
     * @param                   $uri
     * @param                   $data
     *
     * @throws ClientException
     * @throws GuzzleException
     *
     * @return array
     */
    private function checkErrorsAndRetryRequest(ResponseInterface $response, $retryCounter, $uri, $data): array
    {
        if ($response->getStatusCode() == 403) {
            throw ClientException::invalidCredentials();
        } elseif ($response->getStatusCode() == 500) {
            throw ClientException::internalServerError();
        } elseif ($response->getStatusCode() == 404) {
            throw ClientException::invalidURLOrHost();
        } elseif ($response->getStatusCode() == 400) {
            if ($retryCounter < 10) {
                sleep(3);

                return $this->postRequest($uri, $data, $retryCounter);
            } else {
                throw ClientException::errorConnectingToEnvironment();
            }
        }

        throw ClientException::unknownError('Unknown error detected');
    }

    /**
     * @param $response
     *
     * @return void
     */
    private function setSessionIdFromMetaData(ResponseInterface $response): void
    {
        $this->sessionId = str_replace('JSESSIONID=', '', explode(';', $response->getHeaderLine('Set-Cookie'))[0]);
    }

    /**
     * @param $response
     * @param $retryCounter
     * @param $uri
     * @param $data
     *
     * @throws ClientException
     * @throws GuzzleException
     *
     * @return array
     */
    private function processResponse(ResponseInterface $response, $retryCounter, $uri, $data): array
    {
        $responseString = $response->getBody()->getContents();

        $this->setSessionIdFromMetaData($response);

        if ($responseString === false) {
            $this->checkErrorsAndRetryRequest($response, $retryCounter, $uri, $data);
        }

        $decrypted_response = $this->tripleDESService->decrypt($responseString);

        return json_decode($decrypted_response, true);
    }
}
