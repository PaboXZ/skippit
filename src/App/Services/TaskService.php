<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TaskService 
{
    public function __construct(
        private Database $db
        ) {}

    public function getTasksByID(int|string $id){
        return $this->db->query('SELECT * FROM tasks WHERE thread_id = :threadID', ['threadID' => $id])->findAll();
    }

    public function getTaskByID(int|string $id){
        return $this->db->query('SELECT * FROM tasks WHERE id = :ID', ['ID' => $id])->find();
    }

    public function create(array $data) {
        $this->db->query('INSERT INTO tasks (title, content, power, user_id, thread_id) VALUE (:name, :content, :priority, :userID, :threadID)', $data);
    }

    public function delete(int|string $id) {
        $this->db->query('DELETE FROM tasks WHERE id = :id', ['id' => $id]);
    }
}