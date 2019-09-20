<?php

declare(strict_types=1);

namespace Greenlyst\BaseCommerce\Traits;

use function ArrayHelpers\array_has;

trait HasErrorMessages
{
    /**
     * @var array
     */
    private $messages = [];

    /**
     * @param $message
     */
    public function addMessage($message): void
    {
        $this->messages[] = $message;
    }

    /**
     * @param array $response
     */
    public function handleMessages(array $response): void
    {
        if (array_has($response, "exception")) {

            foreach ($response['exception'] as $key => $errorMessage) {
                $this->addMessage($errorMessage);
            }
        }
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }
}