<?php
require_once __DIR__ . '/../config/database.php';

class ProjectModel {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function getAllProjects() {
        $stmt = $this->db->query("SELECT p.*, c.name as client_name, pm.name as pm_name FROM projects p 
                                  JOIN users c ON p.client_id = c.id 
                                  JOIN users pm ON p.pm_id = pm.id 
                                  ORDER BY p.created_at DESC");
        return $stmt->fetchAll();
    }

    public function getProjectsByPM($pm_id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as client_name FROM projects p 
                                    JOIN users c ON p.client_id = c.id 
                                    WHERE p.pm_id = ? ORDER BY p.created_at DESC");
        $stmt->execute([$pm_id]);
        return $stmt->fetchAll();
    }

    public function getProjectsByClient($client_id) {
        $stmt = $this->db->prepare("SELECT p.*, pm.name as pm_name FROM projects p 
                                    JOIN users pm ON p.pm_id = pm.id 
                                    WHERE p.client_id = ? ORDER BY p.created_at DESC");
        $stmt->execute([$client_id]);
        return $stmt->fetchAll();
    }

    public function getProjectsByProgrammer($programmer_id) {
        $stmt = $this->db->prepare("SELECT DISTINCT p.*, c.name as client_name, pm.name as pm_name 
                                    FROM projects p 
                                    JOIN tasks t ON p.id = t.project_id 
                                    JOIN users c ON p.client_id = c.id 
                                    JOIN users pm ON p.pm_id = pm.id 
                                    WHERE t.programmer_id = ? ORDER BY p.created_at DESC");
        $stmt->execute([$programmer_id]);
        return $stmt->fetchAll();
    }

    public function getProjectById($id) {
        $stmt = $this->db->prepare("SELECT p.*, c.name as client_name, pm.name as pm_name FROM projects p 
                                    JOIN users c ON p.client_id = c.id 
                                    JOIN users pm ON p.pm_id = pm.id 
                                    WHERE p.id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function createProject($data) {
        $stmt = $this->db->prepare("INSERT INTO projects (title, description, client_id, pm_id, deadline, status) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['client_id'],
            $data['pm_id'],
            $data['deadline'],
            $data['status'] ?? 'planning'
        ]);
    }

    public function updateProject($id, $data) {
        $stmt = $this->db->prepare("UPDATE projects SET title = ?, description = ?, client_id = ?, pm_id = ?, status = ?, deadline = ? WHERE id = ?");
        return $stmt->execute([
            $data['title'],
            $data['description'],
            $data['client_id'],
            $data['pm_id'],
            $data['status'],
            $data['deadline'],
            $id
        ]);
    }

    public function deleteProject($id) {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function recalculateProgress($project_id) {
        // Calculate percentage of tasks that are 'done'
        $stmt = $this->db->prepare("SELECT 
            COUNT(*) as total, 
            SUM(CASE WHEN status = 'done' THEN 1 ELSE 0 END) as done_count 
            FROM tasks WHERE project_id = ?");
        $stmt->execute([$project_id]);
        $stats = $stmt->fetch();
        
        $progress = 0;
        if ($stats['total'] > 0) {
            $progress = round(($stats['done_count'] / $stats['total']) * 100);
        }
        
        $update = $this->db->prepare("UPDATE projects SET progress = ? WHERE id = ?");
        $update->execute([$progress, $project_id]);
        
        return $progress;
    }

    public function getDashboardStats() {
        $stats = [];
        
        // Total Projects
        $stmt = $this->db->query("SELECT COUNT(*) FROM projects");
        $stats['total_projects'] = $stmt->fetchColumn();
        
        // Active Tasks (status != 'done')
        $stmt = $this->db->query("SELECT COUNT(*) FROM tasks WHERE status != 'done'");
        $stats['active_tasks'] = $stmt->fetchColumn();
        
        // Upcoming Deadlines (Projects with deadline in the next 14 days)
        $stmt = $this->db->query("SELECT COUNT(*) FROM projects WHERE deadline >= CURDATE() AND deadline <= DATE_ADD(CURDATE(), INTERVAL 14 DAY)");
        $stats['upcoming_deadlines'] = $stmt->fetchColumn();
        
        return $stats;
    }
}
