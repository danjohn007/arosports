<?php
require_once 'BaseController.php';

class TestController extends BaseController {
    
    public function connection() {
        $tests = [];
        
        // Test 1: Base URL detection
        $tests['base_url'] = [
            'name' => 'Base URL Detection',
            'result' => BASE_URL,
            'status' => !empty(BASE_URL) ? 'success' : 'error'
        ];
        
        // Test 2: Database connection
        $database = new Database();
        $dbTest = $database->testConnection();
        $tests['database'] = [
            'name' => 'Database Connection',
            'result' => $dbTest['message'],
            'status' => $dbTest['success'] ? 'success' : 'error'
        ];
        
        // Test 3: Config file loading
        $tests['config'] = [
            'name' => 'Configuration Loading',
            'result' => 'App Name: ' . APP_NAME . ', Version: ' . APP_VERSION,
            'status' => defined('APP_NAME') ? 'success' : 'error'
        ];
        
        // Test 4: Session functionality
        $sessionTest = session_status() === PHP_SESSION_ACTIVE;
        $tests['session'] = [
            'name' => 'Session Functionality',
            'result' => $sessionTest ? 'Session active' : 'Session not active',
            'status' => $sessionTest ? 'success' : 'error'
        ];
        
        // Test 5: Database tables exist
        try {
            $stmt = $this->db->query("SHOW TABLES LIKE 'usuarios'");
            $tablesExist = $stmt->rowCount() > 0;
            $tests['tables'] = [
                'name' => 'Database Tables',
                'result' => $tablesExist ? 'Tables exist' : 'Tables missing',
                'status' => $tablesExist ? 'success' : 'warning'
            ];
        } catch (Exception $e) {
            $tests['tables'] = [
                'name' => 'Database Tables',
                'result' => 'Error checking tables: ' . $e->getMessage(),
                'status' => 'error'
            ];
        }
        
        $this->loadView('test/connection', ['tests' => $tests]);
    }
}
?>