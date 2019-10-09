<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce;

use Greenlyst\BaseCommerce\Core\TripleDESService;

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
     * @return array
     * @throws ClientException
     *
     */
    public function postRequest($uri, $data, $retryCounter = 0)
    {
        $data = array_merge($this->toArray(), [
            'payload' => $this->getTripleDESService()->encrypt((string) $data),
        ]);

        $response = $this->sendRequest($uri, $data);

        if (!$response) {
            $this->checkErrorsAndRetryRequest($retryCounter, $uri, $data);
        }

        return $this->processResponse($response, $retryCounter, $uri, $data);
    }

    /**
     * @throws ClientException
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
     * @return bool|resource
     */
    private function sendRequest($uri, $data)
    {
        $url = $this->getEndpointURL().$uri;

        $params = [
            'http' => [
                'method'  => 'POST',
                'content' => json_encode($data),
                'header'  => 'Content-type: application/x-www-form-urlencoded',
            ],
        ];

        $ctx = stream_context_create($params);

        ini_set('user_agent', 'GL_BaseCommerceClientPHP/1.0');

        return fopen($url, 'rb', false, $ctx);
    }

//    private function guzzleRequest($uri, $data)
//    {
//        $client = new \GuzzleHttp\Client();
//
//        $client->request('POST', $uri, [
//            'form_params' => $data,
//            'headers'     => [
//                'Content-type' => 'application/x-www-form-urlencoded',
//            ],
//        ]);
//    }

    /**
     * @param $retryCounter
     * @param $uri
     * @param $data
     *
     * @return array
     * @throws ClientException
     *
     */
    private function checkErrorsAndRetryRequest($retryCounter, $uri, $data): array
    {
        $lastError = error_get_last();
        $error = $lastError['message'];

        if (strpos($error, '403') !== false) {
            throw ClientException::invalidCredentials();
        } elseif (strpos($error, '500') !== false) {
            throw ClientException::internalServerError();
        } elseif (strpos($error, '404') != false) {
            throw ClientException::invalidURLOrHost();
        } elseif (strpos($error, '400') != false) {
            if ($retryCounter < 10) {
                sleep(3);

                return $this->postRequest($uri, $data, $retryCounter);
            } else {
                throw ClientException::errorConnectingToEnvironment();
            }
        }

        throw ClientException::unknownError($error);
    }

    /**
     * adapted from http://us.php.net/manual/en/function.stream-get-meta-data.php.
     *
     * @param $response
     *
     * @return void
     */
    private function setSessionIdFromMetaData($response): void
    {
        $meta = stream_get_meta_data($response);
        foreach (array_keys($meta) as $h) {
            $v = $meta[$h];
            if (is_array($v)) {
                foreach (array_keys($v) as $hh) {
                    $vv = $v[$hh];
                    if (is_string($vv) && substr_count($vv, 'JSESSIONID')) {
                        $this->sessionId = substr($vv, strpos($vv, '=') + 1, 24);
                    }
                }
            }
        }
    }

    /**
     * @param $response
     * @param $retryCounter
     * @param $uri
     * @param $data
     *
     * @return array
     * @throws ClientException
     *
     */
    private function processResponse($response, $retryCounter, $uri, $data): array
    {
        $responseString = stream_get_contents($response);

        $this->setSessionIdFromMetaData($response);

        if ($responseString === false) {
            $this->checkErrorsAndRetryRequest($retryCounter, $uri, $data);
        }

        $decrypted_response = $this->tripleDESService->decrypt($responseString);

        $trimmedResponse = trim($decrypted_response, "\x00..\x1F");

        fclose($response);

        return json_decode($trimmedResponse, true);
    }
}
