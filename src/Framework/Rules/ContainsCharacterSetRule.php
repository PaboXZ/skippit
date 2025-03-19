<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;
use InvalidArgumentException;

class ContainsCharacterSetRule implements RuleInterface{
    public function validate(array $data, string $fieldName, array $params): bool{
        $outcome = true;

        if(!isset($params[0]))
            throw new InvalidArgumentException("Invalid argument for CharacterSetRule, field: {$fieldName}");

        if(preg_match('#[a-z]#', $params[0]))
            $outcome = $outcome && preg_match('#[a-z]#', $data[$fieldName]);
    
        if(preg_match('#[A-Z]#', $params[0]))
            $outcome = $outcome && preg_match('#[A-Z]#', $data[$fieldName]);
    
        if(preg_match('#[0-9]#', $params[0]))
            $outcome = $outcome && preg_match('#[0-9]#', $data[$fieldName]);
        
        if(preg_match('#[^a-zA-Z0-9]#', $params[0]))
            $outcome = $outcome && preg_match('#[^a-zA-Z0-9]#', $data[$fieldName]);

        return $outcome;
    }
    public function getMessage(array $data, string $fieldName, array $params): string
    {
        $message = [];

        if(!isset($params[0]))
            throw new InvalidArgumentException("Invalid argument for CharacterSetRule, field: {$fieldName}");

        if(preg_match('#[a-z]#', $params[0]))
            $message[] = ' small letter';
    
        if(preg_match('#[A-Z]#', $params[0]))
            $message[] = ' big letter';
    
        if(preg_match('#[0-9]#', $params[0])){
            $message[] = ' number';
        }
        
        if(preg_match('#[^a-zA-Z0-9]#', $params[0])){
            $message[] = ' special character';
        }

        $message = implode(',', $message);

        return "Field must contain" . $message;
    }
}