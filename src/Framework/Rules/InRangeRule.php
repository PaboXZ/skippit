<?php

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class InRangeRule implements RuleInterface {
    public function validate(array $data, string $fieldName, array $params): bool {
        if(!isset($params[1]))
            throw new InvalidArgumentException("Invalid argument for InRangeRule, field: {$fieldName}");

        if((int) $data[$fieldName] >= (int) $params[0] && (int) $data[$fieldName] <= (int) $params[1])
            return true;
        return false;
    }
    public function getMessage(array $data, string $fieldName, array $params): string {
        return "Must be in range: $params[0], $params[1]";
    }
}