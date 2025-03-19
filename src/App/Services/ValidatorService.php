<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\{ContainsCharacterSetRule, IsEmailRule, LengthInRule, MatchesRule, RequiredRule};
use Framework\Validator;

class ValidatorService {

    public function __construct(private Validator $validator){
        $this->validator->addRule('required', new RequiredRule);
        $this->validator->addRule('lengthIn', new LengthInRule);
        $this->validator->addRule('isEmail', new IsEmailRule);
        $this->validator->addRule('matches', new MatchesRule);
        $this->validator->addRule('containsCharacterSet', new ContainsCharacterSetRule);
    }

    public function validateRegister(array $data){
        $this->validator->validate($data, [
            'login' => ['required', 'lengthIn:3,20'],
            'email' => ['required', 'isEmail'],
            'password' => ['required', 'lengthIn:8,64', 'containsCharacterSet:aA1#'],
            'passwordConfirm' => ['required', 'matches:password'],
            'tos' => ['required']
        ]);
        
    }

    public function validateLogin(array $data){
        $this->validator->validate($data, [
            'login' => ['required'],
            'password' => ['required']
        ]);
    }

    public function validateAddPassword(array $data){
        $this->validator->validate($data, [
            'passwordName' => ['required', 'lengthIn:3,20']
        ]);
    }

    public function validateEditPasswordName(array $data){
        $this->validator->validate($data, [
            'passwordName' => ['lengthIn:3,20']
        ]);
    }
}