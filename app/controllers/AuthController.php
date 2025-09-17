<?php
require_once 'BaseController.php';

class AuthController extends BaseController {
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $this->sanitizeInput($_POST['email']);
            $password = $_POST['password'];
            
            if (empty($email) || empty($password)) {
                $error = 'Todos los campos son obligatorios';
            } else {
                $stmt = $this->db->prepare("SELECT id, nombre, email, password, tipo_usuario FROM usuarios WHERE email = ? AND status = 'activo'");
                $stmt->execute([$email]);
                $user = $stmt->fetch();
                
                if ($user && $this->verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['nombre'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_type'] = $user['tipo_usuario'];
                    
                    $this->redirect('dashboard');
                } else {
                    $error = 'Credenciales inválidas';
                }
            }
        }
        
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user_id'])) {
            $this->redirect('dashboard');
        }
        
        $this->loadView('auth/login', [
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('login');
    }
}
?>