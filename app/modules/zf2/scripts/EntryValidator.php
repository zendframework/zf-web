<?php

class EntryValidator extends Zend_Validate_Abstract
{
    protected $requiredKeys = array(
        'title',
        'author',
        'date',
        'summary',
        'content',
    );

    public function isValid($value)
    {
        if (!is_array($value) && !is_object($value)) {
            return false;
        }

        $value = (object) $value;

        foreach ($this->requiredKeys as $key) {
            if (!isset($value->{$key})) {
                return false;
            }
        }

        return true;
    }
}
