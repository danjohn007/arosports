<?php
require_once 'BaseController.php';

class UserController extends BaseController {
    
    public function index() {
        $this->requireSuperAdmin();
        
        // Get all users
        $stmt = $this->db->prepare("
            SELECT id, nombre, email, tipo_usuario, telefono, status, created_at 
            FROM usuarios 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $users = $stmt->fetchAll();
        
        $this->loadView('users/index', [
            'title' => 'Gestión de Usuarios',
            'users' => $users
        ]);
    }
    
    public function create() {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $email = $this->sanitizeInput($_POST['email']);
            $password = $_POST['password'];
            $tipo_usuario = $this->sanitizeInput($_POST['tipo_usuario']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            
            $errors = [];
            
            // Validation
            if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
            if (empty($email) || !$this->validateEmail($email)) $errors[] = 'Email válido es obligatorio';
            if (empty($password) || strlen($password) < 6) $errors[] = 'La contraseña debe tener al menos 6 caracteres';
            if (empty($tipo_usuario)) $errors[] = 'El tipo de usuario es obligatorio';
            
            // Check if email already exists
            if (empty($errors)) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ?");
                $stmt->execute([$email]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = 'Este email ya está registrado';
                }
            }
            
            if (empty($errors)) {
                $hashedPassword = $this->hashPassword($password);
                
                $stmt = $this->db->prepare("
                    INSERT INTO usuarios (nombre, email, password, tipo_usuario, telefono, direccion) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                if ($stmt->execute([$nombre, $email, $hashedPassword, $tipo_usuario, $telefono, $direccion])) {
                    $this->redirect('users?success=Usuario creado exitosamente');
                } else {
                    $errors[] = 'Error al crear el usuario';
                }
            }
        }
        
        $this->loadView('users/create', [
            'title' => 'Crear Usuario',
            'errors' => isset($errors) ? $errors : null
        ]);
    }
    
    public function edit($id) {
        $this->requireSuperAdmin();
        
        // Get user data
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch();
        
        if (!$user) {
            $this->redirect('users?error=Usuario no encontrado');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $email = $this->sanitizeInput($_POST['email']);
            $tipo_usuario = $this->sanitizeInput($_POST['tipo_usuario']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $status = $this->sanitizeInput($_POST['status']);
            
            $errors = [];
            
            // Validation
            if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
            if (empty($email) || !$this->validateEmail($email)) $errors[] = 'Email válido es obligatorio';
            if (empty($tipo_usuario)) $errors[] = 'El tipo de usuario es obligatorio';
            
            // Check if email already exists (excluding current user)
            if (empty($errors)) {
                $stmt = $this->db->prepare("SELECT COUNT(*) FROM usuarios WHERE email = ? AND id != ?");
                $stmt->execute([$email, $id]);
                if ($stmt->fetchColumn() > 0) {
                    $errors[] = 'Este email ya está registrado por otro usuario';
                }
            }
            
            if (empty($errors)) {
                $stmt = $this->db->prepare("
                    UPDATE usuarios 
                    SET nombre = ?, email = ?, tipo_usuario = ?, telefono = ?, direccion = ?, status = ?
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$nombre, $email, $tipo_usuario, $telefono, $direccion, $status, $id])) {
                    // Update password if provided
                    if (!empty($_POST['password'])) {
                        $hashedPassword = $this->hashPassword($_POST['password']);
                        $stmt = $this->db->prepare("UPDATE usuarios SET password = ? WHERE id = ?");
                        $stmt->execute([$hashedPassword, $id]);
                    }
                    
                    $this->redirect('users?success=Usuario actualizado exitosamente');
                } else {
                    $errors[] = 'Error al actualizar el usuario';
                }
            }
        }
        
        $this->loadView('users/edit', [
            'title' => 'Editar Usuario',
            'user' => $user,
            'errors' => isset($errors) ? $errors : null
        ]);
    }
    
    public function delete($id) {
        $this->requireSuperAdmin();
        
        // Prevent deleting self
        if ($id == $_SESSION['user_id']) {
            $this->redirect('users?error=No puedes eliminar tu propia cuenta');
        }
        
        $stmt = $this->db->prepare("DELETE FROM usuarios WHERE id = ?");
        if ($stmt->execute([$id])) {
            $this->redirect('users?success=Usuario eliminado exitosamente');
        } else {
            $this->redirect('users?error=Error al eliminar el usuario');
        }
    }
}
?>