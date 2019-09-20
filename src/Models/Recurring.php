<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Models;

use function ArrayHelpers\array_get;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Greenlyst\BaseCommerce\ClientException;
use Greenlyst\BaseCommerce\Core\Helpers;
use Greenlyst\BaseCommerce\LogicException;
use Greenlyst\BaseCommerce\Traits\HasClient;
use Greenlyst\BaseCommerce\Traits\HasCustomFields;
use Greenlyst\BaseCommerce\Traits\HasErrorMessages;

final class Recurring
{
    use HasErrorMessages, HasCustomFields, HasClient;

    private $frequency;

    /**
     * @var Carbon
     */
    private $startDate;
    /**
     * @var Carbon
     */
    private $endDate;
    private $status;
    private $transactionId;
    private $card;
    private $amount;

    const FREQUENCY_ANNUALLY = 'ANNUALLY';
    const FREQUENCY_QUARTERLY = 'QUARTERLY';
    const FREQUENCY_MONTHLY = 'MONTHLY';
    const FREQUENCY_BIWEEKLY = 'BIWEEKLY';
    const FREQUENCY_WEEKLY = 'WEEKLY';
    const FREQUENCY_DAILY = 'DAILY';

    const RECURRING_STATUS_ENABLED = 'RECURRINGENABLED';
    const RECURRING_STATUS_FAILED = 'RECURRINGFAILED';
    const RECURRING_STATUS_DISABLED = 'RECURRINGDISABLED';
    const RECURRING_STATUS_COMPLETED = 'RECURRINGCOMPLETED';

    const CUSTOM_FIELD_PREFIX = 'recurring_transaction';

    const URI_CREATE_RECURRING_TRANSACTION = '/pcms/?f=API_processRecurringTransaction';
    const URI_CANCEL_RECURRING_TRANSACTION = '/pcms/?f=API_cancelRecurringTransaction';

    const TRANSACTION_TYPE_SALE = 'SALE';

    /**
     * @return mixed
     */
    public function getFrequency()
    {
        return $this->frequency;
    }

    /**
     * @param mixed $frequency
     */
    public function setFrequency($frequency): void
    {
        $this->frequency = $frequency;
    }

    /**
     * @return string
     */
    public function getStartDate()
    {
        if ($this->startDate == null) {
            return Carbon::now()->addDay()->format('m/d/Y');
        }

        return $this->startDate->addDay()->format('m/d/Y');
    }

    /**
     * @param CarbonInterface $startDate
     */
    public function setStartDate(CarbonInterface $startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return CarbonInterface
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param CarbonInterface $endDate
     */
    public function setEndDate(CarbonInterface $endDate): void
    {
        $this->endDate = $endDate;
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

    protected function getCustomFieldPrefix(): string
    {
        return self::CUSTOM_FIELD_PREFIX;
    }

    private function isVaultTransaction()
    {
        if ($this->getCard() !== null) {
            return !empty($this->getCard()->getToken());
        }

        //TODO: Do the same for ACH Bank Account Token as well
        return false;
    }

    private function isCardTransaction()
    {
        return $this->getCard() !== null;
    }

    /**
     * @throws LogicException
     * @throws ClientException
     */
    public function createRecurringSale()
    {
        if ($this->isCardTransaction()) {
            if ($this->isVaultTransaction()) {
                validate_array($this->toCreateRecurringArray(), [
                    'bank_card', 'recurring_transaction_frequency', 'recurring_transaction_start_date',
                    'bank_card.bank_card_token',
                ]);
            }
        } else {
            throw LogicException::pendingImplementation('ACH Bank Transaction implementation');
        }

        $response = $this->client->postRequest(self::URI_CREATE_RECURRING_TRANSACTION, json_encode($this->toCreateRecurringArray()));

        $instance = $this->fromRecurringArray($response['recurring_transaction']);

        $instance->handleMessages($response);

        return $instance;
    }

    /**
     * @throws LogicException
     * @throws ClientException
     */
    public function cancelRecurringSale()
    {
        Helpers::validateArray($this->toCancelRecurringArray(), ['recurring_transaction_id']);

        $response = $this->client->postRequest(self::URI_CANCEL_RECURRING_TRANSACTION, array_get($this->toCancelRecurringArray(), 'recurring_transaction_id'));

        $instance = $this->fromRecurringArray($response['recurring_transaction']);

        $instance->handleMessages($response);

        return $instance;
    }

    private function fromRecurringArray(array $data)
    {
        $instance = new static();

        $instance->setFrequency(array_get($data, 'recurring_transaction_frequency'));
        $instance->setStartDate(Carbon::parse(array_get($data, 'recurring_transaction_start_date')));
        $instance->setEndDate(Carbon::parse(array_get($data, 'recurring_transaction_end_date')));
        $instance->setStatus(array_get($data, 'recurring_transaction_status.transaction_status_name'));
        $instance->setTransactionId(array_get($data, 'recurring_transaction_id'));

        return $instance;
    }

    private function toCreateRecurringArray()
    {
        return clear_array(array_merge($this->getCustomFields(), [
            'recurring_transaction_frequency'  => $this->getFrequency(),
            'recurring_transaction_start_date' => $this->getStartDate(),
            'recurring_transaction_end_date'   => $this->getEndDate() ? $this->getEndDate() : null,
            'recurring_transaction_amount'     => $this->getAmount(),
            'bank_card'                        => $this->getCard()->toCreateCardArray(),
            'recurring_transaction_type'       => self::TRANSACTION_TYPE_SALE,
        ]));
    }

    private function toCancelRecurringArray()
    {
        return clear_array([
            'recurring_transaction_id' => $this->getTransactionId(),
        ]);
    }
}
