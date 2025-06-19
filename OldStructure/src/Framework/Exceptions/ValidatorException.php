<?php

declare(strict_types=1);

namespace Framework\Exceptions;

use RuntimeException;

class ValidatorException extends RuntimeException {

    public function __construct(public array $errors){

    }
}