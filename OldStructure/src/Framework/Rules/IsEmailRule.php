<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class IsEmailRule implements RuleInterface {
    public function validate(array $data, string $fieldName, array $params): bool
    {
        return (bool) filter_var($data[$fieldName], FILTER_VALIDATE_EMAIL);
    }
    public function getMessage(array $data, string $fieldName, array $params): string
    {
        return "Invalid format";  
    }
}