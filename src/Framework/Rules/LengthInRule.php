<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class LengthInRule implements RuleInterface {
    public function validate(array $data, string $fieldName, array $params): bool
    {
        if(!isset($params[0]) || empty($params[1]))
            throw new InvalidArgumentException("Ivalid parameters for {$fieldName}");

        return strlen($data[$fieldName]) >= (int) $params[0] && strlen($data[$fieldName]) <= $params[1]; 
    }
    public function getMessage(array $data, string $fieldName, array $params): string
    {
        return "Invalid length ({$params[0]}-{$params[1]})";
    }
}