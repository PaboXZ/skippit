<?php

declare(strict_types=1);

namespace Framework;

use Framework\Contracts\RuleInterface;
use Framework\Exceptions\ValidatorException;

class Validator {
    private array $rules = [];

    public function __construct(){

    }

    public function addRule(string $name, RuleInterface $rule){
        $this->rules[$name] = $rule; 
    }

    public function validate(array $data, array $rules){

        $errors = [];

        foreach($rules as $fieldName => $ruleSet){
            
            foreach($ruleSet as $rule){
                $params = [];

                if(str_contains($rule, ':')){
                    [$rule, $params] = explode(':', $rule);
                    $params = explode(',', $params);
                }

                if(!$this->rules[$rule]->validate($data, $fieldName, $params ?? []))
                    $errors[$fieldName][] = $this->rules[$rule]->getMessage($data, $fieldName, $params);

            }

        }
        if(count($errors))
            throw new ValidatorException($errors);
    }
}