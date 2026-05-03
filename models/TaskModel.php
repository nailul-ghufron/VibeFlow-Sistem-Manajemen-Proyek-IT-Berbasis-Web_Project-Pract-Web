<?php
require_once __DIR__ . '/../config/database.php';

class TaskModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getTasksByProject($project_id) {
        $stmt = $this->db->prepare("SELECT t.*, u.name as programmer_name, u.avatar as programmer_avatar 
                                    FROM tasks t 
                                    JOIN users u ON t.programmer_id = u.id 
                                    WHERE t.project_id = ? ORDER BY t.created_at ASC");
        $stmt->execute([$project_id]);
        return $stmt->fetchAll();
    }

    public function getTaskById($id) {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createTask($data) {
        $stmt = $this->db->prepare("INSERT INTO tasks (project_id, programmer_id, title, description, priority, due_date) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['project_id'],
            $data['programmer_id'],
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['due_date']
        ]);
    }

    public function updateTaskStatus($task_id, $status) {
        $stmt = $this->db->prepare("UPDATE tasks SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $task_id]);
    }

    public function updateTask($id, $data) {
        $stmt = $this->db->prepare("UPDATE tasks SET programmer_id = ?, title = ?, description = ?, priority = ?, status = ?, due_date = ? WHERE id = ?");
        return $stmt->execute([
            $data['programmer_id'],
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['status'],
            $data['due_date'],
            $id
        ]);
    }

    public function deleteTask($id) {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
