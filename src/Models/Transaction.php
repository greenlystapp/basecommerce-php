<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use function ArrayHelpers\array_get;
use function ArrayHelpers\array_set;
use Carbon\Carbon;
use Greenlyst\BaseCommerce\ClientException;
use Greenlyst\BaseCommerce\Core\Helpers;
use Greenlyst\BaseCommerce\LogicException;
use Greenlyst\BaseCommerce\Traits\HasClient;
use Greenlyst\BaseCommerce\Traits\HasErrorMessages;

final class Transaction
{
    use HasClient, HasErrorMessages;

    private $transactionId;
    private $status;
    private $authorizationDate;
    private $capturedDate;
    private $amount;
    private $taxAmount;
    private $tipAmount;
    private $transactionType;
    private $responseCode;
    private $responseMessage;
    private $card;

    const TRANSACTION_STATUS_FAILED = 'FAILED';
    const TRANSACTION_STATUS_CREATED = 'CREATED';
    const TRANSACTION_STATUS_AUTHORIZED = 'AUTHORIZED';
    const TRANSACTION_STATUS_CAPTURED = 'CAPTURED';
    const TRANSACTION_STATUS_SETTLED = 'SETTLED';
    const TRANSACTION_STATUS_VOIDED = 'VOIDED';
    const TRANSACTION_STATUS_DECLINED = 'DECLINED';
    const TRANSACTION_STATUS_3D_SECURE = '3DSECURE';
    const TRANSACTION_STATUS_VERIFIED = 'VERIFIED';

    const TRANSACTION_TYPE_AUTHORIZE = 'AUTH';
    const TRANSACTION_TYPE_CAPTURE = 'CAPTURE';
    const TRANSACTION_TYPE_CREDIT = 'CREDIT';
    const TRANSACTION_TYPE_REFUND = 'REFUND';
    const TRANSACTION_TYPE_SALE = 'SALE';
    const TRANSACTION_TYPE_VOID = 'VOID';

    const URI_CARD_TRANSACTION = '/pcms/?f=API_processBankCardTransactionV4';

    /**
     * @return mixed
     */
    public function getTransactionId()
    {
        return $this->transactionId;
    }

    /**
     * @param mixed $transactionId
     */
    public function setTransactionId($transactionId): void
    {
        $this->transactionId = $transactionId;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getAuthorizationDate()
    {
        return $this->authorizationDate;
    }

    /**
     * @param mixed $authorizationDate
     */
    public function setAuthorizationDate($authorizationDate): void
    {
        $this->authorizationDate = $authorizationDate;
    }

    /**
     * @return mixed
     */
    public function getCapturedDate()
    {
        return $this->capturedDate;
    }

    /**
     * @param mixed $capturedDate
     */
    public function setCapturedDate($capturedDate): void
    {
        $this->capturedDate = $capturedDate;
    }

    /**
     * @return mixed
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount): void
    {
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getTaxAmount()
    {
        return $this->taxAmount;
    }

    /**
     * @param mixed $taxAmount
     */
    public function setTaxAmount($taxAmount): void
    {
        $this->taxAmount = $taxAmount;
    }

    /**
     * @return mixed
     */
    public function getTipAmount()
    {
        return $this->tipAmount;
    }

    /**
     * @param mixed $tipAmount
     */
    public function setTipAmount($tipAmount): void
    {
        $this->tipAmount = $tipAmount;
    }

    /**
     * @return mixed
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * @param mixed $transactionType
     */
    public function setTransactionType($transactionType): void
    {
        $this->transactionType = $transactionType;
    }

    /**
     * @return mixed
     */
    public function getResponseCode()
    {
        return $this->responseCode;
    }

    /**
     * @param mixed $responseCode
     */
    public function setResponseCode($responseCode): void
    {
        $this->responseCode = $responseCode;
    }

    /**
     * @return mixed
     */
    public function getResponseMessage()
    {
        return $this->responseMessage;
    }

    /**
     * @param mixed $responseMessage
     */
    public function setResponseMessage($responseMessage): void
    {
        $this->responseMessage = $responseMessage;
    }

    /**
     * @return Card
     */
    public function getCard()
    {
        return $this->card;
    }

    /**
     * @param Card $card
     */
    public function setCard($card): void
    {
        $this->card = $card;
    }

    public function isVaultTransaction()
    {
        if ($this->getCard() !== null) {
            return !empty($this->getCard()->getToken());
        }

        //TODO: Do the same for ACH Bank Account Token as well
        return false;
    }

    public function isCardTransaction()
    {
        return $this->getCard() !== null;
    }

    /**
     * @throws LogicException
     * @throws ClientException
     */
    public function authorize()
    {
        if ($this->isVaultTransaction()) {
            Helpers::validateArray($this->toAuthorizeTransactionArray(), ['bank_card_transaction_type', 'token', 'bank_card_transaction_amount']);
        } else {
            Helpers::validateArray($this->toAuthorizeTransactionArray(), [
                'bank_card_transaction_name', 'bank_card_transaction_card_number', 'bank_card_transaction_expiration_month',
                'bank_card_transaction_expiration_year', 'bank_card_transaction_type', 'bank_card_transaction_amount',
            ]);
        }

        return $this->processTransaction($this->toAuthorizeTransactionArray());
    }

    /**
     * @throws ClientException
     * @throws LogicException
     *
     * @return $this
     */
    public function capture()
    {
        Helpers::validateArray($this->toCaptureTransactionArray(), ['bank_card_transaction_id', 'bank_card_transaction_amount', 'bank_card_transaction_type']);

        return $this->processTransaction($this->toCaptureTransactionArray());
    }

    /**
     * @throws LogicException
     * @throws ClientException
     */
    public function createSale()
    {
        if ($this->isVaultTransaction()) {
            Helpers::validateArray($this->toCreateTransactionArray(), ['bank_card_transaction_type', 'token', 'bank_card_transaction_amount']);
        } else {
            Helpers::validateArray($this->toCreateTransactionArray(), [
                'bank_card_transaction_name', 'bank_card_transaction_card_number', 'bank_card_transaction_expiration_month',
                'bank_card_transaction_expiration_year', 'bank_card_transaction_type', 'bank_card_transaction_amount',
            ]);
        }

        return $this->processTransaction($this->toCreateTransactionArray());
    }

    /**
     * @throws LogicException
     * @throws ClientException
     */
    public function refundSale()
    {
        Helpers::validateArray($this->toRefundTransactionArray(), ['bank_card_transaction_id', 'bank_card_transaction_amount', 'bank_card_transaction_type']);

        return $this->processTransaction($this->toRefundTransactionArray());
    }

    /**
     * @param $data
     *
     * @throws ClientException
     *
     * @return $this
     */
    private function processTransaction($data)
    {
        $response = $this->client->postRequest(self::URI_CARD_TRANSACTION, json_encode($data));

        $instance = $this->fromArray($response['bank_card_transaction']);

        $instance->handleMessages($response);

        return $instance;
    }

    /**
     * @return array
     */
    private function toRefundTransactionArray(): array
    {
        return clear_array([
            'bank_card_transaction_id'     => $this->getTransactionId(),
            'bank_card_transaction_amount' => $this->getAmount(),
            'bank_card_transaction_type'   => self::TRANSACTION_TYPE_REFUND,
        ]);
    }

    private function toCaptureTransactionArray(): array
    {
        $data = $this->toRefundTransactionArray();

        array_set($data, 'bank_card_transaction_type', self::TRANSACTION_TYPE_CAPTURE);

        return $data;
    }

    /**
     * @throws LogicException
     *
     * @return array
     */
    private function toCreateTransactionArray(): array
    {
        if ($this->isCardTransaction()) {
            if ($this->isVaultTransaction()) {
                return clear_array([
                    'bank_card_transaction_type'   => self::TRANSACTION_TYPE_SALE,
                    'token'                        => $this->getCard()->getToken(),
                    'bank_card_transaction_amount' => $this->getAmount(),
                ]);
            } else {
                return clear_array([
                    'bank_card_transaction_name'             => $this->getCard()->getName(),
                    'bank_card_transaction_card_number'      => $this->getCard()->getCardNumber(),
                    'bank_card_transaction_expiration_month' => $this->getCard()->getCardExpirationMonth(),
                    'bank_card_transaction_expiration_year'  => $this->getCard()->getCardExpirationYear(),
                    'bank_card_transaction_billing_address'  => $this->getCard()->getBillingAddress() ? $this->getCard()->getBillingAddress()->toArray() : null,
                    'bank_card_transaction_type'             => self::TRANSACTION_TYPE_SALE,
                    'bank_card_transaction_amount'           => $this->getAmount(),
                ]);
            }
        } else {
            throw LogicException::pendingImplementation('ACH Bank Transaction implementation');
        }
    }

    /**
     * @throws LogicException
     *
     * @return array
     */
    private function toAuthorizeTransactionArray(): array
    {
        if ($this->isCardTransaction()) {
            $data = $this->toCreateTransactionArray();

            array_set($data, 'bank_card_transaction_type', self::TRANSACTION_TYPE_AUTHORIZE);

            return $data;
        } else {
            throw LogicException::methodCallNotSupported('ACH Processing');
        }
    }

    private function fromArray(array $data)
    {
        $instance = new static();

        $instance->setTransactionId(array_get($data, 'bank_card_transaction_id'));
        $instance->setStatus(array_get($data, 'bank_card_transaction_status.bank_card_transaction_status_name'));
        $instance->setAmount(array_get($data, 'bank_card_transaction_amount', 0));
        $instance->setTipAmount(array_get($data, 'bank_card_transaction_tip_amount', 0));
        $instance->setTaxAmount(array_get($data, 'bank_card_transaction_tax_amount', 0));
        $instance->setAuthorizationDate(Carbon::parse(array_get($data, 'bank_card_transaction_authorization_date'))->toDateTime());
        $instance->setCapturedDate(Carbon::parse(array_get($data, 'bank_card_transaction_capture_date'))->toDateTime());
        $instance->setTransactionType(array_get($data, 'bank_card_transaction_type.bank_card_transaction_type_name'));
        $instance->setResponseCode(array_get($data, 'bank_card_transaction_response_code'));
        $instance->setResponseMessage(array_get($data, 'bank_card_transaction_response_message'));

        return $instance;
    }
}
