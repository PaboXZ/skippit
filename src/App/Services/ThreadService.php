<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class ThreadService {
    public function __construct(
        private Database $db
    )
    {
        
    }

    public function getUsersThreads(int $userID)
    {
        return $this->db->query('SELECT * FROM connection_user_thread INNER JOIN threads ON connection_user_thread.thread_id = threads.id WHERE connection_user_thread.user_id = :userID', ['userID' => $userID])->findAll();
    }

    public function getUsersThreadByID(int $threadID) {
        return $this->db->query('SELECT * FROM connection_user_thread INNER JOIN threads ON connection_user_thread.thread_id = threads.id WHERE connection_user_thread.user_id = :userID AND connection_user_thread.thread_id = :threadID', ['userID' => $_SESSION['user'], 'threadID' => $threadID])->find();
    }

    public function create(string $name){
        $this->db->query('INSERT INTO threads (owner_id, name) VALUE (:user, :name)', ['user' => $_SESSION['user'], 'name' => $name]);
        $threadID = $this->db->query('SELECT id FROM threads WHERE owner_id = :user ORDER BY id DESC', ['user' => $_SESSION['user']])->find()['id'];
        $this->db->query('INSERT INTO connection_user_thread (user_id, thread_id, view_power, is_owner, edit_permission, delete_permission, create_power, complete_permission) VALUE (:user, :thread, 5, 1, 1, 1, 5, 1)', ['user' => $_SESSION['user'], 'thread' => $threadID]);
        $_SESSION['message'] = "Created thread: $name";
    }

    public function getCount(){
        return $this->db->query('SELECT * FROM threads WHERE owner_id = :user', ['user' => $_SESSION['user']])->count();
    }
    public function get(){
        return $this->db->query('SELECT * FROM threads WHERE owner_id = :user', ['user' => $_SESSION['user']])->findAll();
    }

}