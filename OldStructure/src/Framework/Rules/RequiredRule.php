<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class RequiredRule implements RuleInterface{
    public function validate(array $data, string $fieldName, array $params): bool{
        return !empty($data[$fieldName]);
    }
    public function getMessage(array $data, string $fieldName, array $params): string
    {
        return "Field required";
    }
}