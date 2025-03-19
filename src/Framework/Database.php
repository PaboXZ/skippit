<?php

declare(strict_types=1);

namespace Framework;
use \PDO;
use PDOException;
use PDOStatement;

class Database {
    private PDO $connection;
    private PDOStatement $statement;

    public function __construct(string $driver, array $config, string $username, string $password, int $fetchMode = PDO::FETCH_ASSOC){
        
        $config = http_build_query($config, arg_separator: ';');

        $dsn = $driver . ':' . $config;
        
        try{
            $this->connection = new PDO($dsn, $username, $password, [PDO::ATTR_DEFAULT_FETCH_MODE => $fetchMode]);
        }
        catch(PDOException $e){
            exit("Server unaviable");
        }
    }

    public function query(string $query, array $params = []){
        $this->statement = $this->connection->prepare($query);

        $this->statement->execute($params);

        return $this;
    }
    
    public function count(){
        return $this->statement->fetchColumn();
    }

    public function find() {
        return $this->statement->fetch();
    }

    public function findAll(){
        return $this->statement->fetchAll();
    }
}