<?php
require_once 'BaseController.php';

class FraccionamientoController extends BaseController {
    
    public function index() {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->loadView('fraccionamientos/index', [
                'title' => 'Gestión de Fraccionamientos',
                'fraccionamientos' => [],
                'demo_mode' => true
            ]);
            return;
        }
        
        // Get all fraccionamientos
        $stmt = $this->db->prepare("
            SELECT id, nombre, direccion, telefono, email, responsable, comision_porcentaje, status, created_at 
            FROM fraccionamientos 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        $fraccionamientos = $stmt->fetchAll();
        
        $this->loadView('fraccionamientos/index', [
            'title' => 'Gestión de Fraccionamientos',
            'fraccionamientos' => $fraccionamientos
        ]);
    }
    
    public function create() {
        $this->requireSuperAdmin();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision = floatval($_POST['comision_porcentaje']);
            
            if (empty($nombre) || empty($direccion) || empty($responsable)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } else {
                if ($this->db) {
                    $stmt = $this->db->prepare("
                        INSERT INTO fraccionamientos (nombre, direccion, telefono, email, responsable, comision_porcentaje) 
                        VALUES (?, ?, ?, ?, ?, ?)
                    ");
                    
                    if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision])) {
                        $this->redirect('fraccionamientos');
                    } else {
                        $error = 'Error al crear el fraccionamiento';
                    }
                } else {
                    $error = 'Base de datos no disponible';
                }
            }
        }
        
        $this->loadView('fraccionamientos/create', [
            'title' => 'Nuevo Fraccionamiento',
            'error' => isset($error) ? $error : null
        ]);
    }
    
    public function edit($id) {
        $this->requireSuperAdmin();
        
        if (!$this->db) {
            $this->redirect('fraccionamientos');
            return;
        }
        
        // Get fraccionamiento data
        $stmt = $this->db->prepare("SELECT * FROM fraccionamientos WHERE id = ?");
        $stmt->execute([$id]);
        $fraccionamiento = $stmt->fetch();
        
        if (!$fraccionamiento) {
            $this->redirect('fraccionamientos');
            return;
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle form submission
            $nombre = $this->sanitizeInput($_POST['nombre']);
            $direccion = $this->sanitizeInput($_POST['direccion']);
            $telefono = $this->sanitizeInput($_POST['telefono']);
            $email = $this->sanitizeInput($_POST['email']);
            $responsable = $this->sanitizeInput($_POST['responsable']);
            $comision = floatval($_POST['comision_porcentaje']);
            $status = $_POST['status'];
            
            if (empty($nombre) || empty($direccion) || empty($responsable)) {
                $error = 'Todos los campos obligatorios deben completarse';
            } else {
                $stmt = $this->db->prepare("
                    UPDATE fraccionamientos 
                    SET nombre = ?, direccion = ?, telefono = ?, email = ?, responsable = ?, comision_porcentaje = ?, status = ?
                    WHERE id = ?
                ");
                
                if ($stmt->execute([$nombre, $direccion, $telefono, $email, $responsable, $comision, $status, $id])) {
                    $this->redirect('fraccionamientos');
                } else {
                    $error = 'Error al actualizar el fraccionamiento';
                }
            }
        }
        
        $this->loadView('fraccionamientos/edit', [
            'title' => 'Editar Fraccionamiento',
            'fraccionamiento' => $fraccionamiento,
            'error' => isset($error) ? $error : null
        ]);
    }
}
?>