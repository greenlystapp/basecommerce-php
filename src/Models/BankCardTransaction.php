<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use Greenlyst\BaseCommerce\Client;
use Greenlyst\BaseCommerce\ClientException;
use Greenlyst\BaseCommerce\Contracts\Arrayable;
use GuzzleHttp\Exception\GuzzleException;

class BankCardTransaction extends Arrayable
{
    /**
     * @var Client
     */
    private $client;
    private $billingAddress;
    private $shippingAddress;
    private $type;
    private $cardNumber;
    private $cardName;
    private $cardExpiryMonth;
    private $cardExpiryYear;
    private $cardCVV2;
    private $cardTrack1Data;
    private $cardTrack2Data;
    private $ipAddress;
    private $poNumber;
    private $authorizationCode;
    private $responseCode;
    private $status;
    private $responseMessage;
    private $cvvResponseCode;
    private $encryptedTrackData;
    private $merchantTransactionId;
    private $bankCardTransactionVerificationCompleteUrl;
    private $bankCardTransactionVerificationUrl;
    private $amount;
    private $taxAmount;
    private $tipAmount;
    private $transactionId;
    private $bankCardTransactionSettlementBatchId;
    private $recurringTransactionId;
    private $checkSecureCode;
    private $bankCardTransactionRecurringIndicator;
    private $token;
    private $customField1;
    private $customField2;
    private $customField3;
    private $customField4;
    private $customField5;
    private $customField6;
    private $customField7;
    private $customField8;
    private $customField9;
    private $customField10;
    private $messages;
    private $bankCardTransactionSettlementDate;
    private $bankCardTransactionCreationDate;
    private $cipherPayUuid;
    private $fundingInstructions;
    private $transactionSettlementDescriptor;
    private $transactionAuthorizationDescriptor;
    private $authenticated = false;
    private $fundingDate;
    private $eci_Indicator;
    private $xid;
    private $cavv;
    private $chipCardData;
    private $cardSequenceNumber;
    private $emvFallbackReason;

    const URI_PROCESS_BANK_CARD_TRANSACTION = '/pcms/?f=API_processBankCardTransactionV4';

    /**
     * BankCardTransaction constructor.
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }



    /**
     * @throws ClientException
     * @throws GuzzleException
     */
    public function processTransaction()
    {
        $data = array_merge($this->client->toArray(), [
            'payload' => $this->client->getTripleDESService()->encrypt($this->toArray())
        ]);

        $jsonData = json_encode($data);

        $response = $this->client->postRequest(self::URI_PROCESS_BANK_CARD_TRANSACTION, [
            'form_params' => $jsonData
        ]);

        return $this->fromArray($response);
    }

    public function fromArray(array $data)
    {
        $instance = new static($this->client);

        return $instance;
    }

    public function toArray(): array
    {
        return [];
    }
}