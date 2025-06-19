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
            'login_r' => ['required', 'lengthIn:3,20'],
            'email' => ['required', 'isEmail'],
            'password_r' => ['required', 'lengthIn:8,64'],
            'password_confirm' => ['required', 'matches:password_r'],
            'tos' => ['required']
        ]);
        
    }

    public function validateLogin(array $data){
        $this->validator->validate($data, [
            'login' => ['required'],
            'password' => ['required']
        ]);
    }

    public function validateThread(array $data){
        $this->validator->validate($data, [
            'thread_name' => ['required', 'lengthIn:3,20']
        ]);
    }
}