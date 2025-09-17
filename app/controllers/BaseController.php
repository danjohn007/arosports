<?php
class BaseController {
    protected $db;
    
    public function __construct() {
        $database = new Database();
        $this->db = $database->connect();
    }
    
    protected function loadView($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Load header
        include 'app/views/includes/header.php';
        
        // Load main view
        include 'app/views/' . $view . '.php';
        
        // Load footer
        include 'app/views/includes/footer.php';
    }
    
    protected function loadViewOnly($view, $data = []) {
        // Extract data to variables
        extract($data);
        
        // Load only the view without header/footer
        include 'app/views/' . $view . '.php';
    }
    
    protected function redirect($url) {
        header('Location: ' . BASE_URL . ltrim($url, '/'));
        exit();
    }
    
    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('login');
        }
    }
    
    protected function requireSuperAdmin() {
        $this->requireAuth();
        if ($_SESSION['user_type'] !== 'superadmin') {
            $this->redirect('dashboard');
        }
    }
    
    protected function jsonResponse($data, $status = 200) {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    protected function sanitizeInput($input) {
        return htmlspecialchars(strip_tags(trim($input)));
    }
    
    protected function validateEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL);
    }
    
    protected function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    protected function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>