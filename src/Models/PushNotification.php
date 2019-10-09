<?php

namespace Greenlyst\BaseCommerce\Models;

use ArrayHelpers\Arr;
use Greenlyst\BaseCommerce\Core\TripleDESService;

class PushNotification
{
    public const NOTIFICATION_ACH_CHANGE = 'ACH CHANGE';
    public const NOTIFICATION_BANK_CARD_TRANSACTION = 'BANK CARD TRANSACTION';

    private $notificationId;
    private $data;

    /**
     * PushNotification constructor.
     *
     * @param $notificationData
     * @param $sdkKey
     */
    public function __construct($notificationData, $sdkKey)
    {
        $encryption = new TripleDESService($sdkKey);

        $this->data = json_decode($encryption->decrypt($notificationData), true);
    }

    /**
     * Handles the data and returns the appropriate Model.
     *
     * @return Transaction|null
     */
    public function handle()
    {
        $this->notificationId = Arr::get($this->data, 'push_notification_id');

        if ($this->getNotificationType() == self::NOTIFICATION_BANK_CARD_TRANSACTION) {
            return Transaction::arrayToInstance(Arr::get($this->data, 'push_notification_bank_card_transaction'));
        }
    }

    public function getNotificationType()
    {
        return Arr::get($this->data, 'push_notification_type');
    }
}
