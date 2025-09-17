<?php
require_once 'BaseController.php';

class ClubController extends BaseController {
    
    public function index() {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->loadView('clubs/index', [
                'title' => 'Gestión de Clubes',
                'clubs' => [],
                'demo_mode' => true
            ]);
            return;
        }
        
        // Get all clubs
        $stmt = $this->db->prepare("
            SELECT id, nombre, direccion, telefono, email, responsable, comision_porcentaje, status, created_at 
            FROM clubes 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $clubs = $stmt->fetchAll();
        
        $this->loadView('clubs/index', [
            'title' => 'Gestión de Clubes',
            'clubs' => $clubs
        ]);
    }
    
    public function create() {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->db) {
                $this->redirect('clubs?error=Base de datos no disponible');
            }
            
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision_porcentaje = floatval($_POST['comision_porcentaje']);
            
            $errors = [];
            
            // Validation
            if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
            if (!empty($email) && !$this->validateEmail($email)) $errors[] = 'Email debe ser válido';
            if ($comision_porcentaje < 0 || $comision_porcentaje > 100) $errors[] = 'El porcentaje de comisión debe estar entre 0 y 100';
            
            if (empty($errors)) {
                $stmt = $this->db->prepare("
                    INSERT INTO clubes (nombre, direccion, telefono, email, responsable, comision_porcentaje) 
                    VALUES (?, ?, ?, ?, ?, ?)
                ");
                
                if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision_porcentaje])) {
                    $this->redirect('clubs?success=Club creado exitosamente');
                } else {
                    $errors[] = 'Error al crear el club';
                }
            }
        }
        
        $this->loadView('clubs/create', [
            'title' => 'Crear Club',
            'errors' => isset($errors) ? $errors : null
        ]);
    }
    
    public function edit($id) {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->redirect('clubs?error=Base de datos no disponible');
        }
        
        // Get club data
        $stmt = $this->db->prepare("SELECT * FROM clubes WHERE id = ?");
        $stmt->execute([$id]);
        $club = $stmt->fetch();
        
        if (!$club) {
            $this->redirect('clubs?error=Club no encontrado');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision_porcentaje = floatval($_POST['comision_porcentaje']);
            $status = $this->sanitizeInput($_POST['status']);
            
            $errors = [];
            
            // Validation
            if (empty($nombre)) $errors[] = 'El nombre es obligatorio';
            if (!empty($email) && !$this->validateEmail($email)) $errors[] = 'Email debe ser válido';
            if ($comision_porcentaje < 0 || $comision_porcentaje > 100) $errors[] = 'El porcentaje de comisión debe estar entre 0 y 100';
            
            if (empty($errors)) {
                $stmt = $this->db->prepare("
                    UPDATE clubes 
                    SET nombre = ?, direccion = ?, telefono = ?, email = ?, responsable = ?, comision_porcentaje = ?, status = ?
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision_porcentaje, $status, $id])) {
                    $this->redirect('clubs?success=Club actualizado exitosamente');
                } else {
                    $errors[] = 'Error al actualizar el club';
                }
            }
        }
        
        $this->loadView('clubs/edit', [
            'title' => 'Editar Club',
            'club' => $club,
            'errors' => isset($errors) ? $errors : null
        ]);
    }
}
?>