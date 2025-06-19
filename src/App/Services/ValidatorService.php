<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Rules\{ContainsCharacterSetRule, IsEmailRule, LengthInRule, MatchesRule, RequiredRule, InRangeRule};
use Framework\Validator;

class ValidatorService {

    public function __construct(private Validator $validator){
        $this->validator
            ->addRule('required', new RequiredRule)
            ->addRule('lengthIn', new LengthInRule)
            ->addRule('isEmail', new IsEmailRule)
            ->addRule('matches', new MatchesRule)
            ->addRule('containsCharacterSet', new ContainsCharacterSetRule)
            ->addRule('inRange', new InRangeRule);
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

    public function validateTask(array $data, int $userPower){
        $this->validator->validate($data, [
            'task_title' => ['required', 'lengthIn:3,127'],
            'task_content' => ['required', 'lengthIn:0,2024'],
            'task_power' => ['required', "inRange:1,$userPower"]
        ]);
    }
}