<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class MatchesRule implements RuleInterface{
    public function validate(array $data, string $fieldName, array $params): bool{
        if(!isset($params[0]) || !isset($data[$params[0]]))
            throw new InvalidArgumentException("Invalid argument for matches rule, field: {$fieldName}");

        return $data[$fieldName] === $data[$params[0]];
    }
    public function getMessage(array $data, string $fieldName, array $params): string
    {
        return "Fields does not match";
    }
}