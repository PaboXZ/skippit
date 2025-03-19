<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidatorException;

class UserService {

    public function __construct(private Database $database){

    }

    public function isEmailTaken(string $email){
        return (bool) $this->database->query("SELECT * FROM users WHERE email = :email", ['email' => $email])->count();
    }

    public function isLoginTaken(string $login){
        return (bool) $this->database->query("SELECT * FROM users WHERE login = :login", ['login' => $login])->count();
    }

    public function create(array $data){
        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);

        $this->database->query("INSERT INTO users (login, email, password_hash) VALUE (:login, :email, :passwordHash)", [
            'login' => $data['login'],
            'email' => $data['email'],
            'passwordHash' => $passwordHash
        ]);
    }

    public function getUser(array $data){
        return $this->database->query("SELECT * FROM users WHERE login = :login", ['login' => $data['login']])->find();
    }
    
    public function getUserByID(string $userID){
        return $this->database->query("SELECT * FROM users WHERE id = :userID", ['userID' => $userID])->find();
    }

    public function login(array $data){

        $user = $this->getUser($data);
        
        if(!$user)
            throw new ValidatorException(['password' => ["Invalid credentials"]]);

        if(!password_verify($data['password'], $user['password_hash']))
            throw new ValidatorException(['password' => ["Invalid credentials"]]);

        $_SESSION['user'] = $user['id'];
        $_SESSION['userPassword'] = $data['password'];

        session_regenerate_id();
    }

    public function register(array $data){
        
        $errors = [];

        if($this->isLoginTaken($data['login']))
            $errors['login'][] = "Login already taken";

        if($this->isEmailTaken($data['email']))
            $errors['email'][] = "Email already registered";

        if(!empty($errors))
            throw new ValidatorException($errors);

        $this->create($data);

        session_regenerate_id();
    }
}