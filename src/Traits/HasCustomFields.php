<?php

namespace Greenlyst\BaseCommerce\Traits;

use Greenlyst\BaseCommerce\LogicException;

trait HasCustomFields
{
    private $customFields = [];

    abstract protected function getCustomFieldPrefix(): string;

    /**
     * @param $value
     *
     * @throws LogicException
     */
    public function addCustomField($value)
    {
        if ($this->getCustomFieldPrefix() === '') {
            throw LogicException::noCustomFieldPrefixDefined();
        }

        if (count($this->customFields) == 10) {
            throw LogicException::only10CustomFieldsAreAllowed();
        }

        $this->customFields[$this->getCustomFieldPrefix().'_custom_field'.(count($this->customFields) + 1)] = $value;
    }

    public function getCustomFields()
    {
        return $this->customFields;
    }
}
