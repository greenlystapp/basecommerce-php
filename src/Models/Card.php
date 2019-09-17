<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use Carbon\Carbon;
use Greenlyst\BaseCommerce\ClientException;
use Greenlyst\BaseCommerce\Traits\HasClient;
use GuzzleHttp\Exception\GuzzleException;
use function ArrayHelpers\array_get;
use function ArrayHelpers\array_has;

class Card
{
    use HasClient;

    private $name;
    private $cardNumber;
    private $cardExpirationMonth;
    private $cardExpirationYear;
    private $token;
    private $status;
    private $billingAddress;
    private $creationDate;
    private $lastUsedDate;
    private $messages;
    private $alias;
    private $expirationDate;

    const CUSTOM_FIELD_PREFIX_BANK_CARD_TRANSACTION = 'bank_card_transaction';
    const CUSTOM_FIELD_PREFIX_RECURRING_TRANSACTION = 'recurring_transaction';

    const STATUS_DELETED = 'DELETED';

    const URI_ADD_BANK_CARD = '/pcms/?f=API_addBankCardV4';
    const URI_UPDATE_BANK_CARD = '/pcms/?f=API_updateBankCardV4';
    const URI_DELETE_BANK_CARD = '/pcms/?f=API_deleteBankCardV4';

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getCardNumber()
    {
        return $this->cardNumber;
    }

    /**
     * @param mixed $cardNumber
     */
    public function setCardNumber($cardNumber): void
    {
        $this->cardNumber = $cardNumber;
    }

    /**
     * @return mixed
     */
    public function getCardExpirationMonth()
    {
        return $this->cardExpirationMonth;
    }

    /**
     * @param mixed $cardExpirationMonth
     */
    public function setCardExpirationMonth($cardExpirationMonth): void
    {
        $this->cardExpirationMonth = $cardExpirationMonth;
    }

    /**
     * @return mixed
     */
    public function getCardExpirationYear()
    {
        return $this->cardExpirationYear;
    }

    /**
     * @param mixed $cardExpirationYear
     */
    public function setCardExpirationYear($cardExpirationYear): void
    {
        $this->cardExpirationYear = $cardExpirationYear;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token): void
    {
        $this->token = $token;
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
     * @return Address
     */
    public function getBillingAddress()
    {
        return $this->billingAddress;
    }

    /**
     * @param Address $billingAddress
     */
    public function setBillingAddress(Address $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    /**
     * @return mixed
     */
    public function getCreationDate()
    {
        return $this->creationDate;
    }

    /**
     * @return mixed
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param mixed $creationDate
     */
    public function setCreationDate($creationDate): void
    {
        $this->creationDate = $creationDate;
    }

    /**
     * @param mixed $messages
     */
    public function setMessages($messages): void
    {
        $this->messages = $messages;
    }

    /**
     * @return mixed
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * @param mixed $alias
     */
    public function setAlias($alias): void
    {
        $this->alias = $alias;
    }

    /**
     * @return Card
     *
     * @throws ClientException
     */
    public function add()
    {
        $response = $this->client->postRequest(self::URI_ADD_BANK_CARD, json_encode($this->toCreateCardArray()));

        $instance = $this->fromCardArray($response['bank_card']);

        return $instance;
    }

    /**
     * @return $this
     * @throws ClientException
     */
    public function update()
    {
        $response = $this->client->postRequest(self::URI_UPDATE_BANK_CARD, json_encode($this->toUpdateCardArray()));

        $instance = $this->fromCardArray($response['bank_card']);

        return $instance;
    }

    public function delete()
    {
        $response = $this->client->postRequest(self::URI_DELETE_BANK_CARD, array_get($this->toDeleteCardArray(), 'bank_card_token'));

        $instance = $this->fromCardArray($response['bank_card']);

        return $instance;
    }

    public function createTransaction()
    {

    }

    public function createTransactionWithoutStoringBankCard()
    {

    }

    public function createRecurringTransaction()
    {

    }


    protected function getCustomFieldPrefix(): string
    {

    }

    private function fromCardArray(array $data)
    {
        $instance = new static();

        $instance->setName(array_get($data, 'bank_card_name'));
        $instance->setCardNumber(array_get($data, 'bank_card_number'));
        $instance->setCardExpirationMonth(array_get($data, 'bank_card_expiration_month'));
        $instance->setCardExpirationYear(array_get($data, 'bank_card_expiration_year'));
        $instance->setToken(array_get($data, 'bank_card_token'));
        $instance->setCardNumber(array_get($data, 'bank_card_number'));
        $instance->setBillingAddress((new Address())->fromArray(array_get($data, 'bank_card_billing_address')));

        $instance->setCreationDate(Carbon::parse(array_get($data, 'bank_card_creation_date_24hr'))->toDateTime());

        if (array_has($data, 'bank_card_status')) {
            if (gettype(array_get($data, 'bank_card_status')) === "array") {
                $instance->setStatus(array_get($data, 'bank_card_status.bank_card_status_name'));
            } else {
                $instance->setStatus(array_get($data, 'bank_card_status'));
            }
        }

        if (array_has($data, 'bank_card_deleted')) {
            $instance->setStatus(self::STATUS_DELETED);
        }

        $instance->setAlias(array_get($data, 'bank_card_alias'));

        return $instance;
    }

    private function toCreateCardArray(): array
    {
        return clear_array([
            'bank_card_name' => $this->getName(),
            'bank_card_number' => $this->getCardNumber(),
            'bank_card_expiration_month' => $this->getCardExpirationMonth(),
            'bank_card_expiration_year' => $this->getCardExpirationYear(),
            'bank_card_token' => $this->getToken(),
            'bank_card_billing_address' => $this->getBillingAddress() ? $this->getBillingAddress()->toArray() : null,
            'bank_card_alias' => $this->getAlias(),
            'messages' => []
        ]);
    }

    private function toUpdateCardArray(): array
    {
        return clear_array([
            'bank_card_expiration_month' => $this->getCardExpirationMonth(),
            'bank_card_expiration_year' => $this->getCardExpirationYear(),
            'bank_card_token' => $this->getToken(),
            'bank_card_billing_address' => $this->getBillingAddress() ? $this->getBillingAddress()->toArray() : null,
        ]);
    }

    public function toDeleteCardArray(): array
    {
        return clear_array([
            'bank_card_token' => $this->getToken()
        ]);
    }
}