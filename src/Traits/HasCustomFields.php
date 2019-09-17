<?php


namespace Greenlyst\BaseCommerce\Traits;


use Greenlyst\BaseCommerce\LogicException;

trait HasCustomFields
{
    private $customFields = [];

    protected abstract function getCustomFieldPrefix(): string;

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

        $this->customFields[] = $value;
    }
}